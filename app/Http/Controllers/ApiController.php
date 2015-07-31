<?php

//namespace CityHapps\Http\Controllers;

use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\ErrorResponse as ApiErrorResponse;
use EchoIt\JsonApi\Exception as ApiException;
//use \Request;

class ApiController extends Controller
{
	public function handleRequest($modelName, $id = null)
	{
	    /**
	     * Create handler name from model name
	     * @var string
	     */
	    $handlerClass = 'CityHapps\\Handlers\\' . ucfirst($modelName) . 'Handler';

	    if (class_exists($handlerClass)) {
	        $url = Request::url();
	        $method = Request::method();
	        $include = ($i = Request::input('include')) ? explode(',', $i) : $i;
	        $sort = ($i = Request::input('sort')) ? explode(',', $i) : $i;
	        $filter = ($i = Request::except('sort', 'include', 'page')) ? $i : [];
	        $content = Request::getContent();

	        $page = Request::input('page');
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
	        $request = new ApiRequest(Request::url(), $method, $id, $content, $include, $sort, $filter, $pageNumber, $pageSize);
	        $handler = new $handlerClass($request);

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
}