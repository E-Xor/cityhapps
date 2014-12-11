<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EventRecord extends Eloquent {

	protected $guarded = array('id','create_at', 'updated_at');

	protected $table = 'events';

	public function users()
	{
		return $this->belongsToMany('User', 'user_event', 'event_id', 'user_id');
	}

	public function categories()
	{
		return $this->belongsToMany('Category', 'event_category', 'event_id', 'category_id');
	}

	public function votes()
	{
		return $this->hasMany('UserEvent', 'event_id', 'id');
	}

	public function recommendedVotes()
	{
		return $this->hasMany('UserEvent', 'event_id', 'event_id');
	}

	public static function eventCount($startDate)
	{

		if ($startDate != null) {
			$date = new DateTime($startDate);
		} else {
			$date = new DateTime();
		}

		return EventRecord::where('event_date', '=', $date->format('Y-m-d'))->count();
	}

	public static function selectEvents($eventParams) {

		$events = EventRecord::with('categories')
				->maxPerDay($eventParams['maxPerDay'])
				->eventID($eventParams['eventID'])
				->eventName($eventParams['eventName'])
				->venueName($eventParams['venueName'])
				->venueAddress($eventParams['venueAddress'])
				->venueName($eventParams['venueCity'])
				->venueName($eventParams['venueState'])
				->venueName($eventParams['venueZip'])
				->description($eventParams['description'])
				->startTime($eventParams['startTime'])
				->dateRange($eventParams['startDate'], $eventParams['endDate'])
				->imageRequired($eventParams['imageRequired'])
				->eventSearch($eventParams['search'])
				->withCategory($eventParams['category'])
				->withUserEvent($eventParams['userID'])
				->orderBy('event_date', 'asc')
				->orderBy('start_time', 'asc')
				->getPage($eventParams['pageSize'], $eventParams['pageCount'], $eventParams['pageShift'])
				->get();

		return $events;

	}

	public static function recommendedEvents($eventParams) {

		$userID = $eventParams['userID'];

		if ($userID != null) {

			$events = EventRecord::with('categories')
					->userCategory($userID)
					->startTime($eventParams['startTime'])
					->dateRange($eventParams['startDate'], $eventParams['endDate'])
					->imageRequired($eventParams['imageRequired'])

					->withRecommendedUserEvent($eventParams['userID'])
					
					->orderBy('event_date', 'asc')
					->orderBy('start_time', 'asc')
					
					->getPage($eventParams['pageSize'], $eventParams['pageCount'], $eventParams['pageShift'])
					
					->get();

			return $events;

		} else {
			return null;
		}

	}

	public function scopeEventID($query, $eventID) {
		if ($eventID != null) {
			return $query->where('id', '=', $eventID);
		} else {
			return $query;
		}
	}

	public function scopeEventName($query, $eventName) {
		if ($eventName != null) {
			return $query->where('event_name', 'like', '%'.$eventName.'%');
		} else {
			return $query;
		}
	}

	public function scopeVenueName($query, $venueName) {
		if ($venueName != null) {
			return $query->where('venue_name', 'like', '%'.$venueName.'%');
		} else {
			return $query;
		}
	}

	public function scopeVenueAddress($query, $venueAddress) {
		if ($venueAddress != null) {
			return $query->where('address', 'like', '%'.$venueAddress.'%');
		} else {
			return $query;
		}
	}

	public function scopeVenueCity($query, $venueCity) {
		if ($venueCity != null) {
			return $query->where('city', 'like', '%'.$venueCity.'%');
		} else {
			return $query;
		}
	}

	public function scopeVenueState($query, $venueState) {
		if ($venueState != null) {
			return $query->where('state', 'like', '%'.$venueState.'%');
		} else {
			return $query;
		}
	}

	public function scopeVenueZip($query, $venueZip) {
		if ($venueZip != null) {
			return $query->where('zip', 'like', '%'.$venueZip.'%');
		} else {
			return $query;
		}
	}

	public function scopeDescription($query, $description) {
		if ($description != null) {
			return $query->where('description', 'like', '%'.$description.'%');
		} else {
			return $query;
		}
	}

	public function scopeStartTime($query, $startTime) {
		/*
		if ($startTime != null) {
			return $query->where('end_time', '>=', $startTime);
		} else {
			return $query;
		}
		*/
	}

	public function scopeDateRange($query, $startDate, $endDate) {

		if ($startDate != null) {
			$query->where('event_date', '>=', $startDate);
		} 

		if ($endDate != null) {
			$query->where('event_date', '<=', $endDate);
		}

		return $query;
	}

	public function scopeImageRequired($query, $imageRequired) {
		if ($imageRequired != null) {
			if ($imageRequired == 'no') {
				return $query; // Stop processing WHERE clause addition and return
			}
		}
		// If the image_required parameter was not set to "no", return only events with an image by default
		return $query->whereNotNull('event_image_url');
	}

	public function scopeWithCategory($query, $categories) {
		if ($categories != null) {
			
			// categories should be an array
			if ((count($categories) > 0) && is_array($categories)) {

				//$categoryIDs = implode(',', $categories);

				return $query->whereHas('categories', function($q) use ($categories) {
					$q->whereIn('category_id', $categories);
				});

			}

		}

		// If no relevant categories were found, return base query
		return $query;
	}

	public function scopeWithUserEvent($query, $userID) {
		if ($userID != null) {

			$joined = $query->with(array('votes' => function($q) use ($userID) {
				$q->where('user_id', '=', $userID);
			})); // use User ID?

			return $joined;

		} else {

			return $query;

		}
	}

	public function scopeWithRecommendedUserEvent($query, $userID) {
		if ($userID != null) {

			$joined = $query->with(array('recommendedVotes' => function($q) use ($userID) {
				$q->where('user_id', '=', $userID);
			})); // use User ID?

			return $joined;

		} else {

			return $query;

		}
	}

	public function scopeGetPage($query, $pageSize, $pageCount, $pageShift) {
		if (($pageSize != null) && ($pageCount != null)) {
			if ($pageSize == '-1') {
				return $query; // returns all records
			} else {
				if ($pageShift != null) {
					return $query->skip(($pageSize * ($pageCount - 1)) + $pageShift)->take($pageSize);
				} else {
					return $query->skip($pageSize * ($pageCount - 1))->take($pageSize);
				}
			}
		} else {
			return $query->take(100);
		}
	}

	public function scopeMaxPerDay($query, $maxPerDay) {
		if ($maxPerDay != null) {
			$topFiveJoin = '(SELECT @position_num := IF(@event_date = event_date, @position_num + 1, 1) AS position, @event_date := event_date AS match_date, e.event_date AS sort_date, e.start_time AS sort_time, e.id AS match_id FROM events e ORDER BY e.event_date ASC, e.start_time ASC) five_events';

			$eventCountJoin = '(SELECT event_date AS count_date, COUNT(*) AS date_event_count FROM events GROUP BY event_date ORDER BY event_date) count_events';

			$joined = $query->join(DB::raw($topFiveJoin), 'five_events.match_id', '=', 'events.id')
							->join(DB::raw($eventCountJoin), 'count_events.count_date', '=', 'events.event_date')
							->where('five_events.position', '<=', $maxPerDay);

			return $joined;
		} else {
			return $query;
		}
	}

	public function scopeEventSearch($query, $search) {
		if ($search != null) {

			$searchTerms = explode(' ', $search);

    		foreach($searchTerms as $term) {
		        $query->where(function($q) use ($term) {
					
					$q->orWhere('event_name', 'LIKE', '%'. $term .'%')
					->orWhere('venue_name', 'LIKE', '%'. $term .'%')
					->orWhere('address', 'LIKE', '%'. $term .'%')
					->orWhere('city', 'LIKE', '%'. $term .'%')
					->orWhere('state', 'LIKE', '%'. $term .'%')
					->orWhere('zip', 'LIKE', '%'. $term .'%')
					->orWhere('description', 'LIKE', '%'. $term .'%')
					
					->orWhereHas('categories', function($c) use ($term) {
						$c->where('name', 'LIKE', '%'. $term .'%');
					});
				});
		    }
		}

		return $query;
	}
	
	public function scopeUserCategory($query, $userID) {
		
		$joined = $query->join('event_category as ec', 'events.id', '=', 'ec.event_id')
						->join('user_categories as uc', 'uc.category_id', '=', 'ec.category_id')
						->where('uc.user_id', '=', $userID);

		return $joined;
	}

	public static function storeEvents() {

		/*
		Active::storeEvents();
		echo("Active events stored.<br />");
		Eventbrite::storeEvents();
		echo("Eventbrite events stored.<br />");
		Eventful::storeEvents();
		echo("Eventful events stored.<br />");
		Meetup::storeEvents();
		echo("Meetup events stored.<br />");
		*/
		
		EventRecord::storeActiveEvents();
		EventRecord::storeEventbriteEvents();
		EventRecord::storeEventfulEvents();
		EventRecord::storeMeetupEvents();
		
	}

	public static function storeActiveEvents() {

		Active::chunk(200, function($events) {

			foreach ($events as $event) {

				if ($event->imageUrlAdr != null) {

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
						
						$eventRecord->end_time = $event->activityEndDate;
						$eventRecord->all_day_flag = $event->AllDayFlag;
						$eventRecord->event_image_url = $event->imageUrlAdr;
						$eventRecord->latitude = $event->lat;
						$eventRecord->longitude = $event->lon;

						if ($event->activityStartDate != null) {
							$eventRecord->event_date = date_format(date_create($event->activityStartDate), "Y-m-d");
							$eventRecord->start_time = date_format(date_create($event->activityStartDate), "Y-m-d H:i:s");
						}

						if ($event->activityEndDate != null) {
							$eventRecord->end_time = date_format(date_create($event->activityEndDate), "Y-m-d H:i:s");
						}

						$eventRecord->save();

						foreach ($event->activeCategories as $category) {

							$categoryExisting = EventCategory::where('category_id', '=', $category->category_id)->where('event_id', '=', $eventRecord->id);
							$categoryRecords = $categoryExisting->get();
							
							if ($categoryRecords->count() < 1) {
								$categoryRecords->push(new EventCategory);
							}

							foreach ($categoryRecords as $categoryRecord) {
								$categoryRecord->category_id = $category->category_id;
								$categoryRecord->event_id = $eventRecord->id;
								$categoryRecord->save();
							}
						}

					}
				}

			} 
		});

	}

	public static function storeEventbriteEvents() {

		Eventbrite::chunk(200, function($events) {

			foreach ($events as $event) {

				if ($event->logo_url != null) {

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
						$eventRecord->all_day_flag = $event->AllDayFlag;
						$eventRecord->event_image_url = $event->logo_url;
						$eventRecord->latitude = $event->latitude;
						$eventRecord->longitude = $event->longitude;

						if ($event->start_local != null) {
							$eventRecord->event_date = date_format(date_create($event->start_local), "Y-m-d");
							$eventRecord->start_time = date_format(date_create($event->start_local), "Y-m-d H:i:s");
						}

						if ($event->end_local != null) {
							$eventRecord->end_time = date_format(date_create($event->end_local), "Y-m-d H:i:s");
						}

						$eventRecord->save();

						foreach ($event->eventbriteCategories as $category) {

							$categoryExisting = EventCategory::where('category_id', '=', $category->category_id)->where('event_id', '=', $eventRecord->id);
							$categoryRecords = $categoryExisting->get();
							
							if ($categoryRecords->count() < 1) {
								$categoryRecords->push(new EventCategory);
							}

							foreach ($categoryRecords as $categoryRecord) {
								$categoryRecord->category_id = $category->category_id;
								$categoryRecord->event_id = $eventRecord->id;
								$categoryRecord->save();
							}
						}
					}
				}

			} 
		});

	}

	public static function storeEventfulEvents() {
		DB::connection()->disableQueryLog();
		//$events = Eventful::all();

		Eventful::chunk(200, function($events) {

			foreach ($events as $event) {

				if ($event->image != null) {

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
						$eventRecord->end_time = $event->stop_time;
						$eventRecord->all_day_flag = $event->all_day;
						$eventRecord->event_image_url = $event->image;
						$eventRecord->latitude = $event->latitude;
						$eventRecord->longitude = $event->longitude;

						if ($event->start_time != null) {
							$eventRecord->event_date = date_format(date_create($event->start_time), "Y-m-d");
							$eventRecord->start_time = date_format(date_create($event->start_time), "Y-m-d H:i:s");
						}

						if ($event->stop_time != null) {
							$eventRecord->end_time = date_format(date_create($event->stop_time), "Y-m-d H:i:s");
						}

						$eventRecord->save();

						foreach ($event->eventfulCategories as $category) {

							$categoryExisting = EventCategory::where('category_id', '=', $category->category_id)->where('event_id', '=', $eventRecord->id);
							$categoryRecords = $categoryExisting->get();
							
							if ($categoryRecords->count() < 1) {
								$categoryRecords->push(new EventCategory);
							}

							foreach ($categoryRecords as $categoryRecord) {
								$categoryRecord->category_id = $category->category_id;
								$categoryRecord->event_id = $eventRecord->id;
								$categoryRecord->save();
							}
						}
					}
				}

			} 
		});

	}

	public static function storeMeetupEvents() {

		Meetup::chunk(200, function($events) {

			foreach ($events as $event) {

				if ($event->photo_url != null) {

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

						if ($event->time != null) {
							if ($event->utc_offset != null) {
								$seconds = ($event->time + $event->utc_offset) / 1000;
							} else {
								$seconds = $event->time / 1000;
							}
							$eventRecord->event_date = date("Y-m-d", $seconds);
							$eventRecord->start_time = date("Y-m-d H:i:s", $seconds);

							if ($event->duration != null) {
								$endSeconds = $seconds + ($event->duration / 1000);
								$eventRecord->end_time = date("Y-m-d H:i:s", $endSeconds);
							} else {
								// According to Meetup documentation, events with no duration should be assumed to be 3 hours long
								$endSeconds = $seconds + (3*60*60);
								$eventRecord->end_time = $eventRecord->end_time = date("Y-m-d H:i:s", $endSeconds);
							}
						}

						$eventRecord->save();

						foreach ($event->meetupCategories as $category) {

							$categoryExisting = EventCategory::where('category_id', '=', $category->category_id)->where('event_id', '=', $eventRecord->id);
							$categoryRecords = $categoryExisting->get();
							
							if ($categoryRecords->count() < 1) {
								$categoryRecords->push(new EventCategory);
							}

							foreach ($categoryRecords as $categoryRecord) {
								$categoryRecord->category_id = $category->category_id;
								$categoryRecord->event_id = $eventRecord->id;
								$categoryRecord->save();
							}
						}
					}
				}

			} 
		});

	}

}