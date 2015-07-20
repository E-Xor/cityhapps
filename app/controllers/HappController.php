<?php

class HappController extends BaseController
{
    public function getHappList()
    {
        $happs = JsonApiFormatterHelper::getHapps(Happ::getHapps());

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

    }

}
