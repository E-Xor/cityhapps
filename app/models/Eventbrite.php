<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Eventbrite extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'eventbrite';

	public function eventbriteCategories()
	{
		return $this->belongsToMany('EventbriteCategory', 'eventbrite_eventbriteCategories', 'eventbrite_id', 'eventbriteCategories_id');
	}

	public static function storeEvents() {

		//Eventful will return an object but we need an array 
		function eventbriteObjectToArray($d) {
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

		$today = new DateTime();

		$start_date = date_sub($today, date_interval_create_from_date_string("30 days"))->format('Y-m-d');

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$events = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?start_date.range_start='.$start_date.'T00:00:00Z&venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF');

		$jsonObj = json_decode( $events );  
		$jsonArray = eventbriteObjectToArray($jsonObj);

		$total = count($jsonArray['events']);

		$eventbriteCategories = EventbriteCategory::all();
		
		for ($i = 1; $i < $total; $i++ ) {
			
			$checkExisting = Eventbrite::where('eventbriteID', '=', $jsonArray['events'][$i]['id']);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new Eventbrite);
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

				// EventbriteCategories
				if ($jsonArray['events'][$i]['category'] != null) {
	
					$eventbriteCategoryID = null;

					foreach ($eventbriteCategories as $eventbriteCategory) {
						if (strtolower($eventbriteCategory->name) == strtolower($jsonArray['events'][$i]['category']['short_name'])) {
							$eventbriteCategoryID = $eventbriteCategory->id;
							break;
						}
					}
					
					if ($eventbriteCategoryID != null) {
						$checkExistingCategories = EventbriteEventbriteCategory::where('eventbriteCategories_id', '=', $eventbriteCategoryID)->where('eventbrite_id', '=', $eventRecord->id);
						$categoryRecords = $checkExistingCategories->get();
						
						if ($categoryRecords->count() < 1) {
							$categoryRecords->push(new EventbriteEventbriteCategory);
						}

						foreach ($categoryRecords as $categoryRecord) {
							$categoryRecord->eventbrite_id = $eventRecord->id;
							$categoryRecord->eventbriteCategories_id = $eventbriteCategoryID;
						}

						$categoryRecord->save();
					}
				}
			}



		}

	}

}