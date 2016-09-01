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
      if (\Auth::check()) {
        Log::Info('BaseController::Controller::authFromToken. Authenticated! Id: '. \Auth::user()->id .', EMail: '. \Auth::user()->email);
        $user = \Auth::user();
        return $user;
      }
      else {
        return false;
      }
    }

    protected function authorizeResource($resource = null) {
        return CanCuratePolicy::can($this->user, $resource);
    }
}
