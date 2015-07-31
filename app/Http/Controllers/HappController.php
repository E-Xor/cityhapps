<?php

use CityHapps\Http\Middleware\JsonApiFormatter;
use CityHapps\Happ;


class HappController extends BaseController
{
    public function getHappList()
    {
        $date      = Input::get('date');
        $timeofday = Input::get('timeofday');
        $agelevel  = Input::get('agelevel');
        $type      = Input::get('type');
        $zip       = Input::get('zip');
        $zipradius = Input::get('zipradius');
        $limit     = Input::get('limit');

        $limit = is_null($limit) ? 50 : $limit;

        $happs = JsonApiFormatter::getHapps(Happ::getHapps($date, $timeofday, $agelevel, $type, $zip, $zipradius, $limit));

        $response = Response::make($happs['happs'], $happs['status_code']);

        $response->header('Content-Type', JsonApiFormatter::getJsonContentType());

        return $response;
    }

    public function getHapp($id)
    {
        $happs = JsonApiFormatter::getHapps(Happ::getHappById($id));

        $response = Response::make($happs['happs'], $happs['status_code']);

        $response->header('Content-Type', JsonApiFormatter::getJsonContentType());

        return $response;
    }

    public function getHappVenue($id)
    {
        $data = JsonApiFormatter::getHappVenue(Happ::getFirstHapp($id));

        $response = Response::make($data['venue'], $data['status_code']);

        $response->header('Content-Type', JsonApiFormatter::getJsonContentType());

        return $response;
    }

}
