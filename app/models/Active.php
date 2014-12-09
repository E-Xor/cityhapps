<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Active extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'active';

	public function activeCategories()
	{
		return $this->belongsToMany('ActiveCategory', 'active_activeCategories', 'active_id', 'activeCategories_id');
	}

	public static function activeObjectToArray($d) {
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
			return array_map('Active::activeObjectToArray', $d);
		}
		else {
			// Return array
			return $d;
		}
	}

	public static function storeEvents($eventParams) {

		$response = '';

		// Active.com API Events Search (http://api.amp.active.com/v2/search)
		// API Key = 'f4kzrwzqywvtcyntepb9zt5f';

		$today = new DateTime();

		$start_date = date_sub($today, date_interval_create_from_date_string("30 days"))->format('Y-m-d');
		//$end_date = date_add($today, date_interval_create_from_date_string("90 days"))->format('Y-m-d');

		$url = 'http://api.amp.active.com/v2/search/?';
		$url .= 'start_date='.$start_date.'..';
		$url .= '&near=Atlanta%2CGA%2CUS';
		$url .= '&sort=distance';
		$url .= '&exclude_children=true';
		$url .= '&api_key=f4kzrwzqywvtcyntepb9zt5f';

		if ($eventParams['page_size'] != null) {
			$url .= '&per_page=' . $eventParams['page_size'];
		} else {
			$url .= '&per_page=100';
		}

		if ($eventParams['page_number'] != null) {
			$url .= '&current_page=' . $eventParams['page_number'];
		}

		$events = file_get_contents($url);

		//$events = file_get_contents('http://api.amp.active.com/v2/search/?start_date='.$start_date.'..&near=Atlanta%2CGA%2CUS&current_page=1&per_page=1000&sort=distance&exclude_children=true&api_key=f4kzrwzqywvtcyntepb9zt5f');

		$jsonObj = json_decode( $events );  
		$jsonArray = Active::activeObjectToArray($jsonObj);

		$total = count($jsonArray['results']);
		$response = $jsonArray['total_results'];

		$activeCategories = ActiveCategory::all();
		
		for ($i = 1; $i < $total; $i++ ) {
			
			$checkExisting = Active::where('assetGuid', '=', $jsonArray['results'][$i]['assetGuid']);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new Active);
			}
			
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

		return $response;

	}

}