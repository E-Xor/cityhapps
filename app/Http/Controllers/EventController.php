<?php 

// require 'vendor/autoload.php';

use Carbon\Carbon;
use CityHapps\Happ;

class EventController extends BaseController {

	public function testEvents() {
		
		Happ::storeEvents();
	}

	/**
	 * Returns a list of events matching the search criteria
	 *
	 * @param bool|false $name
	 *
	 * @return array
	 */
	public function events() {

		$eventParams = array();

		$eventParams['eventID'] = (Input::get('current_id')) ? Input::get( 'current_id' ) : Input::get('id');
        if ($eventParams['eventID'] == Input::get('current_id')) {
            if (Input::get('current_id')) {
                $eventParams['remove_self'] = TRUE;
            }
        }
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
		$eventParams['category'] = Input::get('category'); // Note that this will be an array of values called category[]
		$eventParams['createdAt'] = Input::get('created_at');
		$eventParams['updatedAt'] = Input::get('updated_at');
		$eventParams['source'] = Input::get('source');
		$eventParams['imageRequired'] = Input::get('image_required');
		$eventParams['pageSize'] = Input::get('page_size');
		$eventParams['pageCount'] = Input::get('page_count');
		$eventParams['pageShift'] = Input::get('page_shift');
		$eventParams['maxPerDay'] = Input::get('max_per_day');

		$eventParams['userID'] = input::get('user_id'); // This is a special parameter used to return the "vote" flag for events with a UserEvent vote

		$eventParams['search'] = Input::get('search'); // This is a space-delimited "omni search" term, like "dogs atlanta april"
		$events = Happ::selectEvents($eventParams);

		$meta = array();
		$count = Happ::eventCount($eventParams['startDate']);
		$meta["count"] = $count;

		$results = array("meta" => $meta, "events" => $events);
        if (isset($eventParams['remove_self']) && $eventParams['remove_self']){
            $results = $events;
        }
		
		return $results;
	}


	public function recommendedEvents() {

		$eventParams = array();

		$eventParams['userID'] = Input::get('user_id');
		$eventParams['startTime'] = Input::get('start_time');
		$eventParams['startDate'] = Input::get('start_date');
		$eventParams['endDate'] = Input::get('end_date');
		$eventParams['imageRequired'] = Input::get('image_required');
		$eventParams['pageSize'] = Input::get('page_size');
		$eventParams['pageCount'] = Input::get('page_count');
		$eventParams['pageShift'] = Input::get('page_shift');

		$events = Happ::recommendedEvents($eventParams);

		$meta = array();
		$meta["user_id"] = $eventParams['userID'];

		$results = array("meta" => $meta, "events" => $events);;

		return json_encode($results);
	}

	public function eventsPaged() {

		$events = Happ::paginate(10);
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

		$timeEvents = Happ::where('start_time', ">", $today )
									->where('start_time', '<', $tomorrow)
									->orderBy('start_time', 'asc')
									->get();

		return $timeEvents;

	}


	public function storeEvents() {

		$response = '';

		Happ::storeEvents();

		return $response;

	}

}



?>
