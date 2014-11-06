<?php 


class MeetupController extends BaseController { 

	public function events() {

		// (See http://www.meetup.com/meetup_api/)
		// $app_key = '45246f1914773661d4c48611911505b';

		$event = file_get_contents('https://api.meetup.com/2/open_events.json?city=atlanta&state=GA&country=US&key=45246f1914773661d4c48611911505b');

		return $event;
	}

	public function storeEvents() {

		//Eventful will return an object but we need an array 
		function objectToArray($d) {
			if (is_object($d)) {
				// Gets the properties of the given object
				// with get_object_vars function
				$d = get_object_vars($d);	
			}
	 
			if (is_array($d)) {
				/*
				* Return array converted to object
				* Using __FUNCTION__ (Magic constant)
				* for recursive call
				*/
				return array_map(__FUNCTION__, $d);
			}
			else {
				// Return array
				return $d;
			}
		}

		// (See http://www.meetup.com/meetup_api/)
		// $app_key = '45246f1914773661d4c48611911505b';

		$meetupCategories = MeetupCategory::all();

		foreach ($meetupCategories as $meetupCategory) {

			$events = file_get_contents('https://api.meetup.com/2/open_events.json?city=atlanta&state=GA&country=US&key=45246f1914773661d4c48611911505b&category=' . $meetupCategory->source_category_id);

			$jsonObj = json_decode( $events );  
			$jsonArray = objectToArray($jsonObj);

			$total = count($jsonArray['results']);
		
			for ($i = 1; $i < $total; $i++ ) {
				//one of these fields is expecting a string and geting an array

				$checkExisting = Meetup::where('meetupID', '=', $jsonArray['results'][$i]['id']);
				$eventRecords = $checkExisting->get();
				
				if ($eventRecords->count() < 1) {
					$eventRecords->push(new Meetup);
				}

				// $table->string('url'); // Event URL
				// $table->string('source_id'); // Event ID (from source)
				// $table->string('event_name'); // Event Name
				// $table->string('venue_url'); // Event Venue URL
				// $table->string('venue_name'); // Event Venue Name
				// $table->string('address'); // Event Location Address
				// $table->string('city'); // Event Location City
				// $table->string('state'); // Event Location State
				// $table->string('zip'); // Event Location Zip
				// $table->string('description'); // Event Description
				// $table->string('start_time'); // Event Start Date/Time
				// $table->string('end_time'); // Event End Date/Time
				// $table->string('all_day_flag'); // Event All Day Flag
				// $table->string('event_image_url'); // Event Image
				// $table->string('latitude'); // Event Latitude
				// $table->string('longitude'); // Event Longitude

				foreach ($eventRecords as $eventRecord) {

					$eventRecord->meetupID  	=	$jsonArray['results'][$i]['id'];
					if (isset($jsonArray['results'][$i]['event_url'])) {
						$eventRecord->event_url		=	$jsonArray['results'][$i]['event_url'];
					}
					if (isset($jsonArray['results'][$i]['name'])) {
						$eventRecord->name 			=	$jsonArray['results'][$i]['name'];
					}
					if (isset($jsonArray['results'][$i]['venueURL'])) {
						$eventRecord->venueURL  	=	'';
					}
					if (isset($jsonArray['results'][$i]['venue']['name'])) {
						$eventRecord->venueName		=	$jsonArray['results'][$i]['venue']['name'];
					}
					if (isset($jsonArray['results'][$i]['venue']['address_1'])) {
						$eventRecord->address_1		=	$jsonArray['results'][$i]['venue']['address_1'];
					}
					if (isset($jsonArray['results'][$i]['venue']['city'])) {
						$eventRecord->city			=	$jsonArray['results'][$i]['venue']['city'];
					}
					if (isset($jsonArray['results'][$i]['venue']['state'])) {
						$eventRecord->state			=	$jsonArray['results'][$i]['venue']['state'];
					}
					if (isset($jsonArray['results'][$i]['venue']['zip'])) {
						$eventRecord->zip			=	$jsonArray['results'][$i]['venue']['zip'];
					}
					if (isset($jsonArray['results'][$i]['description'])) {
						$eventRecord->description	=	$jsonArray['results'][$i]['description'];
					}
					if (isset($jsonArray['results'][$i]['time'])) {
						$eventRecord->time			=	$jsonArray['results'][$i]['time'];
					}
					if (isset($jsonArray['results'][$i]['timezone'])) {
						$eventRecord->timezone		=	$jsonArray['results'][$i]['timezone'];
					}
					if (isset($jsonArray['results'][$i]['duration'])) {
						$eventRecord->duration		=	$jsonArray['results'][$i]['duration'];
					}
					$eventRecord->AllDayFlag	=	'';
					if (isset($jsonArray['results'][$i]['photo_url'])) {
						$eventRecord->photo_url		=	$jsonArray['results'][$i]['photo_url'];
					}
					if (isset($jsonArray['results'][$i]['venue']['lat'])) {
						$eventRecord->lat			=	$jsonArray['results'][$i]['venue']['lat'];
					}
					if (isset($jsonArray['results'][$i]['venue']['lon'])) {
						$eventRecord->lon			=	$jsonArray['results'][$i]['venue']['lon'];
					}

					$eventRecord->save();

					// MeetupCategories
					$meetupMeetupCategory = null;
					$meetupMeetupCategory = MeetupMeetupCategory::where('meetup_id', '=', $eventRecord->id)->where('meetupCategories_id', '=', $meetupCategory->id)->first();
					
					if ($meetupMeetupCategory == null) {
						$meetupMeetupCategory = new MeetupMeetupCategory;
						$meetupMeetupCategory->meetup_id = $eventRecord->id;
						$meetupMeetupCategory->meetupCategories_id = $meetupCategory->id;
						$meetupMeetupCategory->save();
					}
				}
			}
		}

	}

}
	



?>