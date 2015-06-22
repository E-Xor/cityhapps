<?php

class VenueController extends BaseController {

	public static function storeVenues()
    {
		VenueRecord::storeVenues();

		return true;
	}
}