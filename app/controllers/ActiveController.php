<?php 


class ActiveController extends BaseController { 

	public function events() {

		// Active.com API Search v2 (http://api.amp.active.com/v2/search)
		// API Key = 'f4kzrwzqywvtcyntepb9zt5f';

		$event = file_get_contents('http://api.amp.active.com/v2/search/?near=Atlanta%2CGA%2CUS&current_page=1&per_page=100&sort=distance&exclude_children=true&api_key=f4kzrwzqywvtcyntepb9zt5f');

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

		// Active.com API Events Search (http://api.amp.active.com/v2/search)
		// API Key = 'f4kzrwzqywvtcyntepb9zt5f';

		$events = file_get_contents('http://api.amp.active.com/v2/search/?near=Atlanta%2CGA%2CUS&current_page=1&per_page=1000&sort=distance&exclude_children=true&api_key=f4kzrwzqywvtcyntepb9zt5f');

		$jsonObj = json_decode( $events );  
		$jsonArray = objectToArray($jsonObj);

		$total = count($jsonArray['results']);

		$activeCategories = ActiveCategory::all();
		
		for ($i = 1; $i < $total; $i++ ) {
			
			$checkExisting = Active::where('assetGuid', '=', $jsonArray['results'][$i]['assetGuid']);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new Active);
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
				if (count($jsonArray['results'][$i]['assetSeoUrls']) > 0) {
					$eventRecord->urlAdr			=	$jsonArray['results'][$i]['assetSeoUrls'][0]['urlAdr'];
				} else {
					$eventRecord->urlAdr			=	$jsonArray['results'][$i]['urlAdr'];
				}
				
				$eventRecord->assetGuid  		=	$jsonArray['results'][$i]['assetGuid'];
				$eventRecord->assetName 		=	$jsonArray['results'][$i]['assetName'];
				$eventRecord->placeUrlAdr  		=	$jsonArray['results'][$i]['place']['placeUrlAdr'];
				$eventRecord->placeName			=	$jsonArray['results'][$i]['place']['placeName'];
				$eventRecord->addressLine1Txt	=	$jsonArray['results'][$i]['place']['addressLine1Txt'];
				$eventRecord->cityName			=	$jsonArray['results'][$i]['place']['cityName'];
				$eventRecord->stateProvinceCode	=	$jsonArray['results'][$i]['place']['stateProvinceCode'];
				$eventRecord->postalCode		=	$jsonArray['results'][$i]['place']['postalCode'];
				
				if (count($jsonArray['results'][$i]['assetDescriptions']) > 0) {
					$eventRecord->description		=	$jsonArray['results'][$i]['assetDescriptions'][0]['description'];
				}

				$eventRecord->activityStartDate	=	$jsonArray['results'][$i]['activityStartDate'];
				$eventRecord->activityEndDate	=	$jsonArray['results'][$i]['activityEndDate'];
				$eventRecord->AllDayFlag		=	'';
				
				if (count($jsonArray['results'][$i]['assetImages']) > 0) {
					$eventRecord->imageUrlAdr		=	$jsonArray['results'][$i]['assetImages'][0]['imageUrlAdr'];
				}

				$eventRecord->lat				=	$jsonArray['results'][$i]['place']['geoPoint']['lat'];
				$eventRecord->lon				=	$jsonArray['results'][$i]['place']['geoPoint']['lon'];
				
				$eventRecord->save();

				// ActiveCategories
				if (count($jsonArray['results'][$i]['assetCategories']) > 0) {

					for ($j = 0; $j < count($jsonArray['results'][$i]['assetCategories']); $j++) {
						
						$activeCategoryID = null;

						foreach ($activeCategories as $activeCategory) {
							if (strtolower($activeCategory->name) == strtolower($jsonArray['results'][$i]['assetCategories'][$j]['category']['categoryName'])) {
								$activeCategoryID = $activeCategory->id;
								break;
							}
						}
						
						if ($activeCategoryID != null) {
							$checkExistingCategories = ActiveActiveCategory::where('activeCategories_id', '=', $activeCategoryID)->where('active_id', '=', $eventRecord->id);
							$categoryRecords = $checkExistingCategories->get();
							
							if ($categoryRecords->count() < 1) {
								$categoryRecords->push(new ActiveActiveCategory);
							}

							foreach ($categoryRecords as $categoryRecord) {
								$categoryRecord->active_id = $eventRecord->id;
								$categoryRecord->activeCategories_id = $activeCategoryID;
							}

							$categoryRecord->save();
						}
					}
				}

			}

		}

	}

}

?>