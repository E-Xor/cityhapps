<?php

namespace CityHapps\Handlers;

use CityHapps\Happ;
use CityHapps\Http\Middleware\HappFilter;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response as BaseResponse;
use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;


class HappHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	protected static $exposedRelations = ['tags', 'ageLevels', 'categories', 'venues'];

	/**
	 * @param ApiRequest $request
	 *
	 * @return \EchoIt\JsonApi\Model || \EchoIt\JsonApi\Illuminate\Pagination\LengthAwarePaginator
	 * @throws ApiException
	 */
	public function handleGet(ApiRequest $request)
	{
		$model = Happ::with('categories')
			->with('tags')
			->with('venue')
			->with('ageLevels');

		return $this->customHandleGet($request, $model);
	}

	private function customHandleGet(ApiRequest $request, $model)
	{
		$total = null;
        $request->pageNumber = !is_null($request->pageNumber) ? $request->pageNumber : 1;
        $request->pageSize = $request->pageSize <= 100 ? $request->pageSize : 100;

		if (empty($request->id)) {
			if (!empty($request->filter)) {
				$model = $this->customHandleFilterRequest($request->filter, $model);
			}
			if (!empty($request->sort)) {
				if ($request->pageNumber) {
					$total = $model->count();
				}
				$model = $this->handleSortRequest($request->sort, $model);
			}
		} else {
			$model->where('id', '=', $request->id);
		}

		try {
			if ($request->pageNumber && empty($request->id)) {
				$results = $this->customHandlePaginationRequest($request, $model, $total);
			} else {
				$results = $model->get();
                $results = $this->resultDataFormatter($results);
			}
		} catch (\Illuminate\Database\QueryException $e) {
			throw new ApiException(
				'Database Request Failed',
				static::ERROR_SCOPE | static::ERROR_UNKNOWN_ID,
				BaseResponse::HTTP_INTERNAL_SERVER_ERROR,
				array('details' => $e->getMessage())
			);
		}

		return $results;
	}

	protected function customHandlePaginationRequest($request, $model, $total = null)
	{
		$page = $request->pageNumber;
		$perPage = $request->pageSize;
		if (!$total) {
			$total = $model->count();
		}
		$results = $model->forPage($page, $perPage)->get(array('*'));
		$results = $this->resultDataFormatter($results);
		$paginator = new LengthAwarePaginator($results, $total, $perPage, $page, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page[number]'
		]);
		$paginator->appends('page[size]', $perPage);
		if (!empty($request->filter)) {
			foreach ($request->filter as $key=>$value) {
				$paginator->appends($key, $value);
			}
		}
		if (!empty($request->sort)) {
			$paginator->appends('sort', implode(',', $request->sort));
		}

		return $paginator;
	}

	/**
	 * @param $startTime
	 * @param $endTime
	 *
	 * @return array $happTime
	 */
	protected function getHappTimeData($startTime, $endTime)
	{
		$happTime = [
			'start' => null,
			'end' => null
		];

		if(!is_null($startTime)) {
			$happTime['start'] = $this->getHappTimeStructure($startTime);
		}
		if(!is_null($endTime)) {
			$happTime['end'] = $this->getHappTimeStructure($endTime);
		}

		return $happTime;
	}

	/**
	 * @param null $time
	 *
	 * @return array
	 */
	private  function getHappTimeStructure($time = null)
	{
		$happTime = [];
		if(!is_null($time)) {

			$currentTime = new \DateTime($time, $this->getSiteTimeZoneObj());
			$localTime = $currentTime->format(\DateTime::ISO8601);
			$currentTime->setTimezone($this->getUtcTimeZoneObj());
			$utcTime = $currentTime->format(\DateTime::ISO8601);

			$happTime  = [
				'timezone' => $this->getSiteTimeZone(),
				'local' => $localTime,
				'utc' => $utcTime,
			];
		}

		return $happTime;
	}

	/**
	 * @return \DateTimeZone $object
	 */
	private function getSiteTimeZoneObj()
	{
		return new \DateTimeZone($this->getSiteTimeZone()) ;
	}

	private function getUtcTimeZoneObj()
	{
		return new \DateTimeZone("UTC");
	}

	/**
	 * @return string timezone America/New_York
	 */
	private function getSiteTimeZone()
	{
		return 'America/New_York';
	}

	protected function resultDataFormatter($happs)
	{
		foreach($happs as $happ) {
			/**
			 * formatted happ start and end times
			 */
			$happTime = $this->getHappTimeData($happ->start_time, $happ->end_time);

            $happ->start = $happTime['start'];
            $happ->end = $happTime['end'];
            $happ->address = [
                'street_1' => $happ->address,
                'city' => $happ->city,
                'state' => $happ->state,
                'zip' => $happ->zip,
            ];
            $happ->geo_data = [
                'latitude' => $happ->latitude,
                'longitude' => $happ->longitude,
                'google_directions_link' => $happ->google_directions_link,
                'google_map_large' => $happ->google_map_large,
            ];

            unset($happ->start_time);
            unset($happ->end_time);
            unset($happ->city);
            unset($happ->state);
            unset($happ->zip);
            unset($happ->latitude);
            unset($happ->longitude);
            unset($happ->google_directions_link);
            unset($happ->google_map_large);
		}

		return $happs;
	}

	public function customHandleFilterRequest($filters, $model)
	{
		foreach ($filters as $key => $value) {
			if($key == 'type' || $key == 'zip') {
                HappFilter::filterByHappColumn($model, $key, $value);
			}
			if($key == 'date') {
				HappFilter::filterDate($model, $value);
			}
			if($key == 'timeofday') {
				HappFilter::filterTimeOfDay($model, $value);
			}
			if($key == 'zipradius') {
				HappFilter::filterZipRadius($model, $value);
			}
			if($key == 'agelevel') {
				HappFilter::filterAgeLevel($model, $value);
			}
			if($key == 'category') {
				$model->whereHas('categories', function($query) use ($value) {
					$query->where('categories.id', $value);
				});
			}
		}

		return $model;
	}
}