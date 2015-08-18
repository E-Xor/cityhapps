<?php

class Eventbrite extends Integration {

	protected $table = 'eventbrite';

	public function eventbriteCategories()
	{
		return $this->belongsToMany('EventbriteCategory', 'eventbrite_eventbriteCategories', 'eventbrite_id', 'eventbriteCategories_id');
	}

	public static function storeEvents($eventParams) {
		$response = '';

		// Build out the JSON event request parameters
		$today = new DateTime();
		$start_date = date_sub($today, date_interval_create_from_date_string("30 days"))->format('Y-m-d');

		$pageNum = 1;
		if ($eventParams['page_number'] != null) {
			$pageNum = $eventParams['page_number'];
		}

		$values = array(
			'page' => $pageNum,
			'start_date.range_start' => $start_date . 'T00:00:00Z',
		);

		// Make the JSON Request
		$jsonArray = Eventbrite::retrieveData('eventbrite', 'events', $values);

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

				/**
				 * Event information
				 */
				$eventRecord->url					=	$jsonArray['events'][$i]['url'];
				$eventRecord->eventbriteID  		=	$jsonArray['events'][$i]['id'];
				$eventRecord->name_text 			=	$jsonArray['events'][$i]['name']['text'];
				$eventRecord->description_text		=	$jsonArray['events'][$i]['description']['html'];
				$eventRecord->start_local			=	$jsonArray['events'][$i]['start']['local'];
				$eventRecord->end_local				=	$jsonArray['events'][$i]['end']['local'];
				$eventRecord->AllDayFlag			=	'';
				$eventRecord->venue_resource_uri  	=	'';
				/**
				 * Venue Information
				 */
				$eventRecord->venue_name			=	$jsonArray['events'][$i]['venue']['name'];
				$eventRecord->address_1				=	$jsonArray['events'][$i]['venue']['address']['address_1'];
				$eventRecord->city					=	$jsonArray['events'][$i]['venue']['address']['city'];
				$eventRecord->region				=	$jsonArray['events'][$i]['venue']['address']['region'];
				$eventRecord->postal_code			=	$jsonArray['events'][$i]['venue']['address']['postal_code'];
				$eventRecord->latitude				=	$jsonArray['events'][$i]['venue']['latitude'];
				$eventRecord->longitude				=	$jsonArray['events'][$i]['venue']['longitude'];
				$eventRecord->venue_id				=	$jsonArray['events'][$i]['venue']['id'];
				$eventRecord->logo_url				=	$jsonArray['events'][$i]['logo']['url'];

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
