<?php 

// require 'vendor/autoload.php';

use Carbon\Carbon;



class EventController extends BaseController { 


	public function testEvents() {
		
		EventRecord::storeEvents();
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
		$events = EventRecord::selectEvents($eventParams);

		$meta = array();
		$count = EventRecord::eventCount($eventParams['startDate']);
		$meta["count"] = $count;

		$results = array("meta" => $meta, "events" => $events);;
		
		return json_encode($results);
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

		$events = EventRecord::recommendedEvents($eventParams);

		$meta = array();
		$meta["user_id"] = $eventParams['userID'];

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

		$response = '';

		/*$eventParams = array();

		$eventParams['page_size'] = '50';
		$eventParams['page_number'] = '1';

		$loopIndex = 0;

		$activeComplete = false;
		$eventbriteComplete = false;
		$eventfulComplete = false;
		$meetupComplete = false;

		$activeTotalResults = 0;
		$activePageCount = 0;
		$eventbriteTotalResults = 0;
		$eventbritePageCount = 0;
		$eventfulTotalResults = 0;
		$eventfulPageCount = 0;
		$meetupTotalResults = 0;
		$meetupPageCount = 0;

		try {
			$activeTotalResults = Active::storeEvents($eventParams);
			if ($activeTotalResults != null) {
				$activePageCount = Ceil((int)$activeTotalResults / 50);
			}
			if ($activePageCount <= 1) {
				$activeComplete = true;
			}
		} catch (Exception $e) {
			$activeComplete = true;
		}

		try {
			$eventbritePageCount = Eventbrite::storeEvents($eventParams);
			if ($eventbritePageCount <= 1) {
				$eventbriteComplete = true;
			}
		} catch (Exception $e) {
			$eventbriteComplete = true;
		}

		try {
			$eventfulPageCount = Eventful::storeEvents($eventParams);
			if ($eventfulPageCount <= 1) {
				$eventfulComplete = true;
			}
		} catch (Exception $e) {
			$eventfulComplete = true;
		}

		try {
			$meetupTotalResults = Meetup::storeEvents($eventParams);
			if ($meetupTotalResults != null) {
				$meetupPageCount = Ceil((int)$meetupTotalResults / 50);
			}
			if ($meetupPageCount <= 1) {
				$meetupComplete = true;
			}
		} catch (Exception $e) {
			$meetupComplete = true;
		}

		$loopIndex = $activePageCount;
		if ($eventbritePageCount > $loopIndex) {
			$loopIndex = $eventbritePageCount;
		}
		if ($eventfulPageCount > $loopIndex) {
			$loopIndex = $eventfulPageCount;
		}
		if ($meetupPageCount > $loopIndex) {
			$loopIndex = $meetupPageCount;
		}

		$response .= "A: " . $activePageCount . " EB: " . $eventbritePageCount . " EF: " . $eventfulPageCount . " M: " . $meetupPageCount . "<br />";
		if (!$activeComplete || !$eventbriteComplete || !$eventfulComplete || !$meetupComplete) {
				
			for ($i = 2; $i <= (int)$loopIndex; $i++) {
				
				$eventParams['page_number'] = $i;

				// ACTIVE
				if (!$activeComplete) {
					try {
						$temp = Active::storeEvents($eventParams);

						$response .= "Active " . $i . "<br />";

						if ($activePageCount <= $i) {
							$activeComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Active failed at " . $i . "<br />";
						$activeComplete = true;
					}
				}

				// EVENTBRITE 
				if (!$eventbriteComplete) {
					try {
						$temp = Eventbrite::storeEvents($eventParams);

						$response .= "Eventbrite " . $i . "<br />";

						if ($eventbritePageCount <= $i) {
							$eventbriteComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Eventbrite failed at " . $i . "<br />";
						$eventbriteComplete = true;
					}
				}

				// EVENTFUL
				if (!$eventfulComplete) {
					try {
						$temp = Eventful::storeEvents($eventParams);

						$response .= "Eventful " . $i . "<br />";

						if ($eventfulPageCount <= $i) {
							$eventfulComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Eventful failed at " . $i . "<br />";
						$eventfulComplete = true;
					}
				}

				// MEETUP
				if (!$meetupComplete) {
					try {
						$temp = Meetup::storeEvents($eventParams);

						$response .= "Meetup " . $i . "<br />";

						if ($meetupPageCount <= $i) {
							$meetupComplete = true;
						}
					} catch (Exception $e) {
						$response .= "Meetup failed at " . $i . "<br />";
						$meetupComplete = true;
					}
				}
			}

		}*/
		

		EventRecord::storeEvents();

		return $response;

	}

}
	



?>