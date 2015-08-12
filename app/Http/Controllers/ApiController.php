<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;
use EchoIt\JsonApi\Request as ApiRequest;
use EchoIt\JsonApi\ErrorResponse as ApiErrorResponse;
use EchoIt\JsonApi\Exception as ApiException;

class ApiController extends Controller
{
	public function handleRequest(Request $request, $modelName, $id = null)
	{
	    /**
	     * Create handler name from model name
	     * @var string
	     */
	    $handlerClass = 'CityHapps\\Handlers\\' . ucfirst($modelName) . 'Handler';

	    if (class_exists($handlerClass)) {
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