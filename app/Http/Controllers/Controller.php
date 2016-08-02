<?php

namespace CityHapps\Http\Controllers;

use Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use JWTAuth;
use Exception;
use CityHapps\CanCuratePolicy;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function authFromToken()
    {
        // Check if the token is set, and if so try to parse it
        if (JWTAuth::getToken()) {
            try {
                if ($user = JWTAuth::parseToken()->authenticate()) {
                    Log::Info('Controller::authFromToken. Auth::login.');
                    \Auth::login($user, true);
                    return $user;
                } else {
                    throw new Exception("Invalid Token");
                }
            } catch (Exception $e) {
                return abort(403, json_encode(['token_invalid']), ['Content-Type' => 'application/json']);
            }
        } else {
            return abort(403, json_encode(['token_invalid']), ['Content-Type' => 'application/json']);
        }
    }

    protected function authorizeResource($resource = null) {
        return CanCuratePolicy::can($this->user, $resource);
    }
}
