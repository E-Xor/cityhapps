<?php 


class MeetupController extends BaseController { 

	public function events() {

		// (See http://www.meetup.com/meetup_api/)
		// $app_key = '45246f1914773661d4c48611911505b';

		$event = file_get_contents('https://api.meetup.com/2/open_events.json?city=atlanta&state=GA&country=US&key=45246f1914773661d4c48611911505b');

		return $event;
	}

	public function storeEvents() {
		Meetup::storeEvents();
	}

}
	



?>