<?php 
namespace CityHapps\Handlers;

use CityHapps\Category;
use DB;

use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;
use CityHapps\Http\Requests\Request;


class CategoryHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	protected static $exposedRelations = ['users', 'happs'];

	/**
	 * @param ApiRequest $request
	 *
	 * @return \EchoIt\JsonApi\Model || \EchoIt\JsonApi\Illuminate\Pagination\LengthAwarePaginator
	 * @throws ApiException
	 */
  public function handleGet(ApiRequest $request, $user = FALSE)
  {
		$categories = DB::table('event_category')->select('category_id')->distinct()->get(); 
		$array = json_decode(json_encode($categories), true);
    $model = Category::whereIn('id', $array)->select('id')->get();
    return $model;
  }
}
