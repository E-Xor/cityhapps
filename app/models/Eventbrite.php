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

	public static function eventbriteObjectToArray($d) {
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
			return array_map('Eventbrite::eventbriteObjectToArray', $d);
		}
		else {
			// Return array
			return $d;
		}
	}

	public static function storeEvents($eventParams) {

		$response = '';

		$today = new DateTime();

		$start_date = date_sub($today, date_interval_create_from_date_string("30 days"))->format('Y-m-d');

		// (See http://developer.eventbrite.com/)
		// $token = 'UKNDTMAVPK4A7ACVVLWF';

		$pageNum = 1;

		if ($eventParams['page_number'] != null) {
			$pageNum = $eventParams['page_number'];
		}

		//$events = file_get_contents($url);

		$events = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?start_date.range_start='.$start_date.'T00:00:00Z&venue.city=Atlanta&venue.region=GA&venue.country=US&token=UKNDTMAVPK4A7ACVVLWF&page=' . $pageNum);

		$jsonObj = json_decode( $events );  
		$jsonArray = Eventbrite::eventbriteObjectToArray($jsonObj);

		$total = count($jsonArray['events']);
		$response = $jsonArray['pagination']['page_count'];

		$eventbriteCategories = EventbriteCategory::all();
		
		for ($i = 1; $i < $total; $i++ ) {
			
			$checkExisting = Eventbrite::where('eventbriteID', '=', $jsonArray['events'][$i]['id']);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new Eventbrite);
			}

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

		return $response;

	}

}