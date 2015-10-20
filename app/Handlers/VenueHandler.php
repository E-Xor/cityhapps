<?php
namespace CityHapps\Handlers;

use CityHapps\Venue;
use Illuminate\Support\Collection;
use CityHapps\Happ;

use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;

class VenueHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	protected static $exposedRelations = ['tags', 'happs'];

	/**
	 * @param ApiRequest $request
	 *
	 * @return \EchoIt\JsonApi\Model || \EchoIt\JsonApi\Illuminate\Pagination\LengthAwarePaginator
	 * @throws ApiException
	 */
	public function handleGet(ApiRequest $request, $user = false)
	{
		$model = Venue::with('tags')->with('happs');
		return $this->handleGetCustom($request, $model);
	}

	/**
     * Custom handling of GET request.
     * Must be called explicitly in handleGet function.
     *
     * @param  EchoIt\JsonApi\Request $request
     * @param  EchoIt\JsonApi\Model $model
     * @return EchoIt\JsonApi\Model|Illuminate\Pagination\LengthAwarePaginator
     */
    protected function handleGetCustom(ApiRequest $request, $model)
    {
        $total = null;
        if (empty($request->id)) {
            if (!empty($request->filter)) {
                $model = $this->handleFilterRequestCustom($request->filter, $model);
            }
            if (!empty($request->sort)) {
                //if sorting AND paginating, get total count before sorting!
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
                $model = Venue::has('happs');
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

    /**
     * Function to handle filtering requests.
     *
     * @param  array $filters key=>value pairs of column and value to filter on
     * @param  EchoIt\JsonApi\Model $model
     * @return EchoIt\JsonApi\Model
     */
    protected function handleFilterRequestCustom($filters, $model)
    {
        foreach ($filters as $key=>$value) {
        	if ($key == 'search') {
        		$query = explode(' ', trim($value));
        		foreach ($query as $query_value) {
        			$model = $model->where('name', 'LIKE', '%' . $query_value . '%');
        		}
        	} else {
	            $model = $model->where($key, '=', $value);
	        }
        }
        return $model;
    }

}
