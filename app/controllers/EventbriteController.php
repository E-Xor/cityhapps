<?php 


class EventbriteController extends BaseController { 

	public function events() {

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$event = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF');

		return $event;
	}

	public function storeEvents() {
		Eventbrite::storeEvents();
	}

}

?>