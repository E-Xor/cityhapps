<?php 

// require 'vendor/autoload.php';

use Carbon\Carbon;



class EventController extends BaseController { 


	public function testEvents() {
		
	}

	public function events() {

		$eventParams = array();

		$eventID = Input::get('id');
		$eventName = Input::get('name');
		$venueName = Input::get('venue_name');
		$venueAddress = Input::get('venue_address');
		$venueCity = Input::get('venue_city');
		$venueState = Input::get('venue_state');
		$venueZip = Input::get('venue_zip');
		$description = Input::get('description');
		$startTime = Input::get('start_time');
		$startDate = Input::get('start_date');
		$endDate = Input::get('end_date');
		$latitude = Input::get('latitude');
		$longitude = Input::get('longitude');
		$category = Input::get('category');
		$createdAt = Input::get('created_at');
		$updatedAt = Input::get('updated_at');
		$source = Input::get('source');

		$pageSize = Input::get('page_size');
		$pageCount = Input::get('page_count');

		$eventParams['eventID'] = $eventID;
		$eventParams['eventName'] = $eventName;

		$events = EventRecord::selectEvents($eventParams);
		return json_encode($events);
	}

	public function eventsPaged() {

		$events = EventRecord::paginate(10);
		return $events;

	}


	public function geoEvents() {

		$currentLat = Input::get('latitude');
		$currentLong = Input::get('longitude');

		$findClosest = Haversine::closestCoords($currentLat, $currentLong);
		
		return $findClosest;		
	}

	

	public function dayEvents() {

		$count = Input::get('next');

		$today = Carbon::today('America/Chicago')->addDays($count);
		$tomorrow = Carbon::tomorrow('America/Chicago')->addDays($count);	

		$timeEvents = EventRecord::where('start_time', ">", $today )
									->where('start_time', '<', $tomorrow)
									->orderBy('start_time', 'asc')
									->get();

		return $timeEvents;

	}


	public function storeEvents() {

		EventRecord::storeEvents();

	}

}
	



?>