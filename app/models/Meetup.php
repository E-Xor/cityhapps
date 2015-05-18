<?php

class Meetup extends Integration {

	protected $table = 'meetup';

	public function meetupCategories()
	{
		return $this->belongsToMany('MeetupCategory', 'meetup_meetupCategories', 'meetup_id', 'meetupCategories_id');
	}

	public static function storeEvents($eventParams) {
		$response = '';

		$meetupCategories = MeetupCategory::all();

		// Build out the JSON event request parameters
		$page = ($eventParams['page_size'] != null) ? $eventParams['page_size'] : 100;
		$offset = ($eventParams['page_number'] != null) ? (int)$eventParams['page_number'] - 1 : 0;

		$values = array(
			'page' => $page,
			'offset' => $offset,
		);

		// Make the JSON Request
		$jsonArray = Meetup::retrieveData('meetup', 'events', $values);

		$total = count($jsonArray['results']);
		$response = $jsonArray['meta']['total_count'];
	
		for ($i = 1; $i < $total; $i++ ) {
			
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
				
				if (isset($jsonArray['results'][$i]['group']['group_photo']['highres_link'])) {
					$eventRecord->photo_url		=	$jsonArray['results'][$i]['group']['group_photo']['highres_link'];
				} elseif (isset($jsonArray['results'][$i]['photo_url'])) {
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