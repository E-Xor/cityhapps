<?php 

// require 'vendor/autoload.php';

use Carbon\Carbon;



class EventController extends BaseController { 


	public function testEvents() {
		
		$user = User::find(9);
		$eventID = '6709';

		$user->events()->attach($eventID, array('vote' => '1'));
	}

	public function events() {

		$eventParams = array();

		$eventParams['eventID'] = Input::get('id');
		$eventParams['eventName'] = Input::get('name');
		$eventParams['venueName'] = Input::get('venue_name');
		$eventParams['venueAddress'] = Input::get('venue_address');
		$eventParams['venueCity'] = Input::get('venue_city');
		$eventParams['venueState'] = Input::get('venue_state');
		$eventParams['venueZip'] = Input::get('venue_zip');
		$eventParams['description'] = Input::get('description');
		$eventParams['startTime'] = Input::get('start_time');
		$eventParams['startDate'] = Input::get('start_date');
		$eventParams['endDate'] = Input::get('end_date');
		$eventParams['latitude'] = Input::get('latitude');
		$eventParams['longitude'] = Input::get('longitude');
		$eventParams['category'] = Input::get('category');
		$eventParams['createdAt'] = Input::get('created_at');
		$eventParams['updatedAt'] = Input::get('updated_at');
		$eventParams['source'] = Input::get('source');
		$eventParams['imageRequired'] = Input::get('image_required');
		$eventParams['pageSize'] = Input::get('page_size');
		$eventParams['pageCount'] = Input::get('page_count');
		$eventParams['pageShift'] = Input::get('page_shift');

		$events = EventRecord::selectEvents($eventParams);
		
		$meta = array();
		$count = EventRecord::eventCount();
		$meta["count"] = $count;

		$results = array("meta" => $meta, "events" => $events);;
		
		return json_encode($results);
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