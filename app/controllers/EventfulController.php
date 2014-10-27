<?php 


class EventfulController extends BaseController { 

	public function events() {

		// Enter your application key here. (See http://api.eventful.com/keys/)
		// $app_key = 'pLB3SGLn9xSnfcg5';

		$event = file_get_contents('http://api.eventful.com/json/events/search?location=Atlanta&app_key=pLB3SGLn9xSnfcg5');
			// &date=today&image_sizes=block250&sort_order=date&sort_direction=descending&app_key=pLB3SGLn9xSnfcg5');

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

		// Enter your application key here. (See http://api.eventful.com/keys/)
		// $app_key = 'pLB3SGLn9xSnfcg5';

		$events = file_get_contents('http://api.eventful.com/json/events/search?location=Atlanta&image_sizes=block250&app_key="pLB3SGLn9xSnfcg5"&date=Future');

		$jsonObj = json_decode( $events );  
		$jsonArray = objectToArray($jsonObj);

		$total = count($jsonArray['events']['event']);
		
		for ($i = 1; $i < $total; $i++ ) {
			//one of these fields is expecting a string and geting an array

			print_r($jsonArray['events']['event'][$i]);
			
			$eventRecord = new EventRecord;

			$eventRecord->watching_count	=	$jsonArray['events']['event'][$i]['watching_count'];
			$eventRecord->olson_path  		=	$jsonArray['events']['event'][$i]['olson_path'];
			$eventRecord->calendar_count 	=	$jsonArray['events']['event'][$i]['calendar_count'];
			$eventRecord->comment_count  	=	$jsonArray['events']['event'][$i]['comment_count'];
			$eventRecord->region_abbr		=	$jsonArray['events']['event'][$i]['region_abbr'];
			$eventRecord->postal_code		=	$jsonArray['events']['event'][$i]['postal_code'];
			$eventRecord->going_count		=	$jsonArray['events']['event'][$i]['going_count'];
			$eventRecord->all_day			=	$jsonArray['events']['event'][$i]['all_day'];
			$eventRecord->latitude			=	$jsonArray['events']['event'][$i]['latitude'];
			$eventRecord->groups			=	$jsonArray['events']['event'][$i]['groups'];
			$eventRecord->url				=	$jsonArray['events']['event'][$i]['url'];
			$eventRecord->eventful_id		=	$jsonArray['events']['event'][$i]['id'];
			$eventRecord->privacy			=	$jsonArray['events']['event'][$i]['privacy'];
			$eventRecord->city_name			=	$jsonArray['events']['event'][$i]['city_name'];
			$eventRecord->link_count		=	$jsonArray['events']['event'][$i]['link_count'];
			$eventRecord->longitude			=	$jsonArray['events']['event'][$i]['longitude'];
			$eventRecord->country_name		=	$jsonArray['events']['event'][$i]['country_name'];
			$eventRecord->country_abbr		=	$jsonArray['events']['event'][$i]['country_abbr'];
			$eventRecord->region_name		=	$jsonArray['events']['event'][$i]['region_name'];
			$eventRecord->start_time		=	$jsonArray['events']['event'][$i]['start_time'];
			$eventRecord->tz_id				=	$jsonArray['events']['event'][$i]['tz_id'];
			$eventRecord->description		=	$jsonArray['events']['event'][$i]['description'];
			$eventRecord->modified			=	$jsonArray['events']['event'][$i]['modified'];
			$eventRecord->venue_display		=	$jsonArray['events']['event'][$i]['venue_display'];
			$eventRecord->tz_country		=	$jsonArray['events']['event'][$i]['tz_country'];
			
			//Needle in haystack- This returned value is sometimes an array needs to be a string.
			// $eventRecord->performers		=	$jsonArray['events']['event'][$i]['performers'];

			$eventRecord->title				=	$jsonArray['events']['event'][$i]['title'];
			$eventRecord->venue_address		=	$jsonArray['events']['event'][$i]['venue_address'];
			$eventRecord->geocode_type		=	$jsonArray['events']['event'][$i]['geocode_type'];
			$eventRecord->tz_olson_path		=	$jsonArray['events']['event'][$i]['tz_olson_path'];
			$eventRecord->recur_string		=	$jsonArray['events']['event'][$i]['recur_string'];
			$eventRecord->calendars			=	$jsonArray['events']['event'][$i]['calendars'];
			$eventRecord->owner				=	$jsonArray['events']['event'][$i]['owner'];
			$eventRecord->going				=	$jsonArray['events']['event'][$i]['going'];
			$eventRecord->country_abbr2		=	$jsonArray['events']['event'][$i]['country_abbr2'];
			// $eventRecord->image			=	$jsonArray['events']['event'][$i]['image'];
			// $event->// caption		=		$jsonArray['events']['event'][$i]['caption'],
			$eventRecord->created			=	$jsonArray['events']['event'][$i]['created'];
			$eventRecord->venue_id			=	$jsonArray['events']['event'][$i]['venue_id'];
			$eventRecord->tz_city			=	$jsonArray['events']['event'][$i]['tz_city'];
			$eventRecord->stop_time			=	$jsonArray['events']['event'][$i]['stop_time'];
			$eventRecord->venue_name		=	$jsonArray['events']['event'][$i]['venue_name'];
			$eventRecord->venue_url			=	$jsonArray['events']['event'][$i]['venue_url'];


			$eventRecord->save();

		}

		// print_r($eventCategories);

	}

	}
	



?>