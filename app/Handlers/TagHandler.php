<?php 
namespace CityHapps\Handlers;

use CityHapps\Tag;

use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;

class TagHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	protected static $exposedRelations = ['venues', 'happs'];

	/**
	 * @param ApiRequest $request
	 *
	 * @return \EchoIt\JsonApi\Model || \EchoIt\JsonApi\Illuminate\Pagination\LengthAwarePaginator
	 * @throws ApiException
	 */
	public function handleGet(ApiRequest $request, $user = false)
	{
		return $this->handleGetDefault($request, new Tag);
	}

}