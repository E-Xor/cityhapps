<?php 


class EventbriteController extends BaseController { 

	public function events() {

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$event = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF');

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

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$events = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF');

		$jsonObj = json_decode( $events );  
		$jsonArray = objectToArray($jsonObj);

		$total = count($jsonArray['events']);
		
		for ($i = 1; $i < $total; $i++ ) {
			//one of these fields is expecting a string and geting an array

			$eventRecord = new Eventbrite;

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

			$eventRecord->url					=	$jsonArray['events'][$i]['url'];
			$eventRecord->eventbriteID  		=	$jsonArray['events'][$i]['id'];
			$eventRecord->name_text 			=	$jsonArray['events'][$i]['name']['text'];
			$eventRecord->venue_resource_uri  	=	'';
			$eventRecord->venue_name			=	$jsonArray['events'][$i]['venue']['name'];
			$eventRecord->address_1				=	$jsonArray['events'][$i]['venue']['address']['address_1'];
			$eventRecord->city					=	$jsonArray['events'][$i]['venue']['address']['city'];
			$eventRecord->region				=	$jsonArray['events'][$i]['venue']['address']['region'];
			$eventRecord->postal_code			=	$jsonArray['events'][$i]['venue']['address']['postal_code'];
			$eventRecord->description_text		=	$jsonArray['events'][$i]['description']['text'];
			$eventRecord->start_local			=	$jsonArray['events'][$i]['start']['local'];
			$eventRecord->end_local				=	$jsonArray['events'][$i]['end']['local'];
			$eventRecord->AllDayFlag			=	'';
			$eventRecord->logo_url				=	$jsonArray['events'][$i]['logo_url'];
			$eventRecord->latitude				=	$jsonArray['events'][$i]['venue']['address']['latitude'];
			$eventRecord->longitude				=	$jsonArray['events'][$i]['venue']['address']['longitude'];
			
			$eventRecord->save();

		}

	}

}

?>