<?php 


class EventController extends BaseController { 

	public function events() {

		// $events = EventRecord::paginate(10);

		$events = EventRecord::all()->toJson();
		return $events;
	}

	public function geoEvents() {

		$currentLat = Input::get('latitude');
		$currentLong = Input::get('longitude');

			$getClosest = DB::table('events')
				->where('latitude', '<', $currentLat)->orderBy('latitude', "desc")
				->orWhere('latitude', '>', $currentLat)->orderBy('latitude', "asc")
				->orWhere('longitude', '<', $currentLong)->orderBy('longitude', "desc")
				->orWhere('longitude', '>', $currentLong)->orderBy('longitude', "asc")

			->take(10)->get();


		//DO NOT REMOVE CODE BELOW
		//	


		// 	$getClosestLatOver = EventRecord::where('latitude', '>', $currentLat)->take(3)->get();

		// 		$getClosestLat = array();
		// 		array_push($getClosestLat, $getClosestLatUnder, $getClosestLatOver);

		// 	$getClosestLongUnder = EventRecord::where('longitude', '<', $currentLong)->take(3)->get();
		// 	$getClosestLongOver = EventRecord::where('longitude', '>', $currentLong)->take(3)->get();

		// 		$getClosestLong = array();
		// 		array_push($getClosestLong, $getClosestLongUnder, $getClosestLongOver);
		
			
		// $returnArray = array();
		// array_push($returnArray, $getClosestLat, $getClosestLong);


		// return $returnArray;

			return $getClosest;
		
	}

	public function storeEvents() {

		$this->storeActiveEvents();
		$this->storeEventbriteEvents();
		$this->storeEventfulEvents();
		$this->storeMeetupEvents();

	}

	public function storeActiveEvents() {

		$events = Active::all();

		foreach ($events as $event) {

			$checkExisting = EventRecord::where('source_id', '=', $event->assetGuid);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new EventRecord);
			}

			foreach ($eventRecords as $eventRecord) {
				
				$eventRecord->source = 'Active';
				$eventRecord->url = $event->urlAdr;
				$eventRecord->source_id = $event->assetGuid;
				$eventRecord->event_name = $event->assetName;
				$eventRecord->venue_url = $event->placeUrlAdr;
				$eventRecord->venue_name = $event->placeName;
				$eventRecord->address = $event->addressLine1Txt;
				$eventRecord->city = $event->cityName;
				$eventRecord->state = $event->stateProvinceCode;
				$eventRecord->zip = $event->postalCode;
				$eventRecord->description = $event->description;
				$eventRecord->start_time = $event->activityStartDate;
				$eventRecord->end_time = $event->activityEndDate;
				$eventRecord->all_day_flag = $event->AllDayFlag;
				$eventRecord->event_image_url = $event->imageUrlAdr;
				$eventRecord->latitude = $event->lat;
				$eventRecord->longitude = $event->lon;

				$eventRecord->save();
			}

		} 

	}

	public function storeEventbriteEvents() {

		$events = Eventbrite::all();

		foreach ($events as $event) {

			$checkExisting = EventRecord::where('source_id', '=', $event->eventbriteID);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new EventRecord);
			}

			foreach ($eventRecords as $eventRecord) {
				
				$eventRecord->source = 'Eventbrite';
				$eventRecord->url = $event->url;
				$eventRecord->source_id = $event->eventbriteID;
				$eventRecord->event_name = $event->name_text;
				$eventRecord->venue_url = $event->venue_resource_uri;
				$eventRecord->venue_name = $event->venue_name;
				$eventRecord->address = $event->address_1;
				$eventRecord->city = $event->city;
				$eventRecord->state = $event->region;
				$eventRecord->zip = $event->postal_code;
				$eventRecord->description = $event->description_text;
				$eventRecord->start_time = $event->start_local;
				$eventRecord->end_time = $event->end_local;
				$eventRecord->all_day_flag = $event->AllDayFlag;
				$eventRecord->event_image_url = $event->logo_url;
				$eventRecord->latitude = $event->latitude;
				$eventRecord->longitude = $event->longitude;

				$eventRecord->save();
			}

		} 

	}

	public function storeEventfulEvents() {

		$events = Eventful::all();

		foreach ($events as $event) {

			$checkExisting = EventRecord::where('source_id', '=', $event->eventful_id);
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new EventRecord);
			}

			foreach ($eventRecords as $eventRecord) {
				
				$eventRecord->source = 'Eventful';
				$eventRecord->url = $event->url;
				$eventRecord->source_id = $event->eventful_id;
				$eventRecord->event_name = $event->title;
				$eventRecord->venue_url = $event->venue_url;
				$eventRecord->venue_name = $event->venue_name;
				$eventRecord->address = $event->venue_address;
				$eventRecord->city = $event->city_name;
				$eventRecord->state = $event->region_abbr;
				$eventRecord->zip = $event->postal_code;
				$eventRecord->description = $event->description;
				$eventRecord->start_time = $event->start_time;
				$eventRecord->end_time = $event->stop_local;
				$eventRecord->all_day_flag = $event->all_day;
				$eventRecord->event_image_url = $event->image;
				$eventRecord->latitude = $event->latitude;
				$eventRecord->longitude = $event->longitude;

				$eventRecord->save();
			}

		} 

	}

	public function storeMeetupEvents() {

		$events = Meetup::all();

		foreach ($events as $event) {

			$checkExisting = EventRecord::where('source_id', '=', $event->meetupID)
										->where('source', '=', 'Meetup');
			$eventRecords = $checkExisting->get();
			
			if ($eventRecords->count() < 1) {
				$eventRecords->push(new EventRecord);
			}

			foreach ($eventRecords as $eventRecord) {
				
				$eventRecord->source = 'Meetup';
				$eventRecord->url = $event->event_url;
				$eventRecord->source_id = $event->meetupID;
				$eventRecord->event_name = $event->name;
				$eventRecord->venue_url = $event->venueURL;
				$eventRecord->venue_name = $event->venueName;
				$eventRecord->address = $event->address_1;
				$eventRecord->city = $event->city;
				$eventRecord->state = $event->state;
				$eventRecord->zip = $event->zip;
				$eventRecord->description = $event->description;
				$eventRecord->start_time = $event->time;
				$eventRecord->end_time = ''; // Need to use time, timezone, and duration
				$eventRecord->all_day_flag = $event->all_day;
				$eventRecord->event_image_url = $event->photo_url;
				$eventRecord->latitude = $event->lat;
				$eventRecord->longitude = $event->lon;

				$eventRecord->save();
			}

		} 

	}



	public function eventsOld() {

		// Enter your application key here. (See http://api.eventful.com/keys/)
		// $app_key = 'pLB3SGLn9xSnfcg5';

		$event = file_get_contents('http://api.eventful.com/json/events/search?location=Atlanta&date=today&image_sizes=block250&sort_order=date&sort_direction=descending&app_key=pLB3SGLn9xSnfcg5');

		return $event;
	}

	public function storeEventsOld() {

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