<?php
namespace CityHapps\Handlers;

use CityHapps\Venue;

use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;

class VenueHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	protected static $exposedRelations = ['tags'];

	/**
	 * @param ApiRequest $request
	 *
	 * @return \EchoIt\JsonApi\Model || \EchoIt\JsonApi\Illuminate\Pagination\LengthAwarePaginator
	 * @throws ApiException
	 */
	public function handleGet(ApiRequest $request)
	{
		return $this->handleGetDefault($request, new Venue);
	}

}