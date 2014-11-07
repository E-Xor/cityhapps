<?php 


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

		// 	$getClosest = DB::table('events')
		// 		->where('latitude', '<', $currentLat)->orderBy('latitude', "desc")
		// 		->orWhere('latitude', '>', $currentLat)->orderBy('latitude', "asc")
		// 		->orWhere('longitude', '<', $currentLong)->orderBy('longitude', "desc")
		// 		->orWhere('longitude', '>', $currentLong)->orderBy('longitude', "asc")

		// 	->take(10)->get();

		

		$findClosest = Haversine::closestCoords($currentLat, $currentLong);

		// DB::enableQueryLog();
		
		return $findClosest;

		//DO NOT REMOVE CODE BELOW
		//	


		// 	$getClosestLatOver = EventRecord::where('latitude', '>', $currentLat)->take(3)->get();

		// 		$getClosestLat = array();
		// 		array_push($getClosestLat, $getClosestLatUnder, $getClosestLatOver);

		// 	$getClosestLongUnder = EventRecord::where('longitude', '<', $currentLong)->take(3)->get();
		// 	$getClosestLongOver = EventRecord::where('longitude', '>', $currentLong)->take(3)->get();

		// 		$getClosestLong = array();
		// 		array_push($getClosestLong, $getClosestLongUnder, $getClosestLongOver);
		
			
		// $returnArray = array();
		// array_push($returnArray, $getClosestLat, $getClosestLong);


		// return $returnArray;

			
		
	}

	public function storeEvents() {

		EventRecord::storeEvents();

	}

}
	



?>