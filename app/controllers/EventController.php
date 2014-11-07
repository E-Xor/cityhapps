<?php 

// require 'vendor/autoload.php';

use Carbon\Carbon;



class EventController extends BaseController { 


	public function testEvents() {
		
	}

	public function events() {

		$events = EventRecord::all()->toJson();
		return $events;
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
									->get();

		return $timeEvents;

	}


	public function storeEvents() {

		EventRecord::storeEvents();

	}

}
	



?>