<?php 
namespace CityHapps\Handlers;

use Symfony\Component\HttpFoundation\Response;
use CityHapps\Models\User;

use EchoIt\JsonApi\Exception as ApiException;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\Handler as ApiHandler;
use Request;

/**
* Handles API requests for Users.
*/
class VenueHandler extends ApiHandler
{
	const ERROR_SCOPE = 1024;

	/*
	* List of relations that can be included in response.
	* (eg. 'friend' could be included with ?include=friend)
	*/
	protected static $exposedRelations = [];

	/**
	 * Handles GET requests. 
	 * @param EchoIt\JsonApi\Request $request
	 * @return EchoIt\JsonApi\Model|Illuminate\Support\Collection|EchoIt\JsonApi\Response|Illuminate\Pagination\LengthAwarePaginator
	 */
	public function handleGet(ApiRequest $request)
	{
	    //you can use the default GET functionality, or override with your own 
	    return $this->handleGetDefault($request, new \Venue);
	}

	/**
	 * Handles PUT requests. 
	 * @param EchoIt\JsonApi\Request $request
	 * @return EchoIt\JsonApi\Model|Illuminate\Support\Collection|EchoIt\JsonApi\Response
	 */
	public function handlePut(ApiRequest $request)
	{
	    //you can use the default PUT functionality, or override with your own
	    return $this->handlePutDefault($request, new \Venue);
	}
}