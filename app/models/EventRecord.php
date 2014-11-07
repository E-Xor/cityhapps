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

	public static function storeEvents() {

		Active::storeEvents();
		echo("Active events stored.<br />");
		Eventbrite::storeEvents();
		echo("Eventbrite events stored.<br />");
		Eventful::storeEvents();
		echo("Eventful events stored.<br />");
		Meetup::storeEvents();
		echo("Meetup events stored.<br />");

		EventRecord::storeActiveEvents();
		echo("Active events added to Events table.<br />");
		EventRecord::storeEventbriteEvents();
		echo("Eventbrite events added to Events table.<br />");
		EventRecord::storeEventfulEvents();
		echo("Eventful events added to Events table.<br />");
		EventRecord::storeMeetupEvents();
		echo("Meetup events added to Events table.<br />");

		echo("All events stored and updated.");
		
	}

	private static function storeActiveEvents() {

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

	private static function storeEventbriteEvents() {

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

	private static function storeEventfulEvents() {

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

	private static function storeMeetupEvents() {

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

}