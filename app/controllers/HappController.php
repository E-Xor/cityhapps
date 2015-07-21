<?php

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


        $happs = JsonApiFormatterHelper::getHapps(Happ::getHapps($date, $timeofday, $agelevel, $type, $zip, $zipradius));

        $response = Response::make($happs['happs'], $happs['status_code']);

        $response->header('Content-Type', JsonApiFormatterHelper::getJsonContentType());

        return $response;
    }

    public function getHapp($id)
    {
        $happs = JsonApiFormatterHelper::getHapps(Happ::getHappById($id));

        $response = Response::make($happs['happs'], $happs['status_code']);

        $response->header('Content-Type', JsonApiFormatterHelper::getJsonContentType());

        return $response;
    }

    public function getHappVenue($id)
    {
        $data = JsonApiFormatterHelper::getHappVenue(Happ::getFirstHapp($id));

        $response = Response::make($data['venue'], $data['status_code']);

        $response->header('Content-Type', JsonApiFormatterHelper::getJsonContentType());

        return $response;
    }

}
