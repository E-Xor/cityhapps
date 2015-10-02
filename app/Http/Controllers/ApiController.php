<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\ErrorResponse as ApiErrorResponse;
use EchoIt\JsonApi\Exception as ApiException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
	/**
	 * Handles the API request
	 */
	public function handleRequest(Request $request, $modelName, $id = null)
	{
	    /**
	     * Create handler name from model name
	     * @var string
	     */
	    if ($modelName == 'agelevel')
	    	$modelName = 'AgeLevel';
	    $handlerClass = 'CityHapps\\Handlers\\' . ucfirst($modelName) . 'Handler';

	    if (class_exists($handlerClass)) {
	    	$user = $this->getAuthUser($request);
	        $url = $request->url();
	        $method = $request->method();
	        $include = ($i = $request->input('include')) ? explode(',', $i) : $i;
	        $sort = ($i = $request->input('sort')) ? explode(',', $i) : $i;
	        $filter = ($i = $request->except('sort', 'include', 'page')) ? $i : [];
	        $content = $request->getContent();

	        $page = $request->input('page');
	        $pageSize = null;
	        $pageNumber = null;
	        if($page) {
	            if(is_array($page) && !empty($page['size']) && !empty($page['number'])) {
	                $pageSize = $page['size'];
	                $pageNumber = $page['number'];
	            } else {
	                 return new ApiErrorResponse(400, 400, 'Expected page[size] and page[number]');
	            }
	        }
	        $request = new ApiRequest($request->url(), $method, $id, $content, $include, $sort, $filter, $pageNumber, $pageSize);
	        $handler = new $handlerClass($request, $user);

	        // A handler can throw EchoIt\JsonApi\Exception which must be gracefully handled to give proper response
	        try {
	            $res = $handler->fulfillRequest();
	        } catch (ApiException $e) {
	            return $e->response();
	        }

	        return $res->toJsonResponse();
	    }

	    // If a handler class does not exist for requested model, it is not considered to be exposed in the API
	    return new ApiErrorResponse(404, 404, 'Entity not found');
	}

	/**
	 * Retrieves the authentication token
	 */
	public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return; 
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /**
     *
     */
    public function getAuthenticatedUser()
    {
        try {
        	if (JWTAuth::getToken()) {
	            if (! $user = JWTAuth::parseToken()->authenticate()) {
	                return response()->json(['user_not_found'], 404);
	            }
	        } else {
	        	return response()->json(['token_absent'], 400);
	        }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    /**
     *
     */
    public function getAuthUser(Request $request)
    {
    	$user = false;

    	// Check if the token is set, and if so try to parse it
    	if (JWTAuth::setRequest($request)->getToken()) {
	        try {
	        	$user = JWTAuth::parseToken()->authenticate();
	        } catch (Exception $e) {
	        	// 
	        }
	    }

        // the token is valid and we have found the user via the sub claim
        return $user;
    }
}
