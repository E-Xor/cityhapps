<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Meetup extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'meetup';

	public function meetupCategories()
	{
		return $this->belongsToMany('MeetupCategory', 'meetup_meetupCategories', 'meetup_id', 'meetupCategories_id');
	}

	public static function meetupObjectToArray($d) {
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
			return array_map('Meetup::meetupObjectToArray', $d);
		}
		else {
			// Return array
			return $d;
		}
	}

	public static function storeEvents($eventParams) {

		// (See http://www.meetup.com/meetup_api/)
		// $app_key = '45246f1914773661d4c48611911505b';

		$response = '';

		$meetupCategories = MeetupCategory::all();

		$url = 'https://api.meetup.com/2/open_events.json?';
		$url .= 'city=atlanta&state=GA&country=US';
		$url .= '&key=45246f1914773661d4c48611911505b';
		$url .= '&fields=category';

		if ($eventParams['page_size'] != null) {
			$url .= '&page=' . $eventParams['page_size'];
		} else {
			$url .= '&page=100';
		}

		if ($eventParams['page_number'] != null) {
			$offset = (int)$eventParams['page_number'] - 1;
			$url .= '&offset=' + $offset;
		}

		$events = file_get_contents($url);

		//$events = file_get_contents('https://api.meetup.com/2/open_events.json?city=atlanta&state=GA&country=US&key=45246f1914773661d4c48611911505b&category=' . $meetupCategory->source_category_id);

		$jsonObj = json_decode( $events );  
		$jsonArray = Meetup::meetupObjectToArray($jsonObj);

		$total = count($jsonArray['results']);
		$response = $jsonArray['meta']['total_count'];
	
		for ($i = 1; $i < $total; $i++ ) {
			//one of these fields is expecting a string and geting an array

			$checkExisting = Meetup::where('meetupID', '=', $jsonArray['results'][$i]['id']);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new Meetup);
			}

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

				if (isset($jsonArray['results'][$i]['utc_offset'])) {
					$eventRecord->utc_offset	=	$jsonArray['results'][$i]['utc_offset'];
				}

				$eventRecord->save();

				// MeetupCategories
				if (isset($jsonArray['results'][$i]['group']['category'])) {
	
					$meetupCategoryID = null;

					foreach ($meetupCategories as $meetupCategory) {
						if (strtolower($meetupCategory->source_category_id) == strtolower($jsonArray['results'][$i]['group']['category']['id'])) {
							$meetupCategoryID = $meetupCategory->id;
							break;
						}
					}
					
					if ($meetupCategoryID != null) {
						$checkExistingCategories = MeetupMeetupCategory::where('meetupCategories_id', '=', $meetupCategoryID)->where('meetup_id', '=', $eventRecord->id);
						$categoryRecords = $checkExistingCategories->get();
						
						if ($categoryRecords->count() < 1) {
							$categoryRecords->push(new MeetupMeetupCategory);
						}

						foreach ($categoryRecords as $categoryRecord) {
							$categoryRecord->meetup_id = $eventRecord->id;
							$categoryRecord->meetupCategories_id = $meetupCategoryID;
						}

						$categoryRecord->save();
					}
				}
			}
		}

		return $response;

	}

}