<?php 
namespace CityHapps\Handlers;

use CityHapps\Happ;
use CityHapps\Http\Middleware\HappFilter;
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
			$model = $model->where('id', '=', $request->id);
		}

		try {
			if ($request->pageNumber && empty($request->id)) {
				$results = $this->handlePaginationRequest($request, $model, $total);
			} else {
				$results = $model->get();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			throw new Exception(
				'Database Request Failed',
				static::ERROR_SCOPE | static::ERROR_UNKNOWN_ID,
				BaseResponse::HTTP_INTERNAL_SERVER_ERROR,
				array('details' => $e->getMessage())
			);
		}
		return $results;
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
		}

		return $model;
	}
}