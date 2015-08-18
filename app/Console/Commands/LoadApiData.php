<?php

namespace CityHapps\Console\Commands;

use Illuminate\Console\Command;
use CityHapps\Happ;
use CityHapps\Venue;
use Eventbrite;
use Eventful;
use Meetup;
use EventCategory;
use Mews\Purifier\Purifier;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LoadApiData extends Command 
{
  /**
   * The threshold score for event inclusion on keywork filters
   *
   * @var integer
   */
  public static $threshold = 20;

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'api:load';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Combines all API tables into the main table';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function fire()
  {
    $this->line("Start time: " . (string)date('l jS \of F Y h:i:s A'));
    $this->line("Loading all API Tables (Eventbrite, Eventful, Meetup) into main table...");
    $this->line("Starting Eventbrite...");
    $this->storeEventbriteEvents();
    $this->line("Eventbrite Done!");
    $this->line("Starting Eventful...");
    $this->storeEventfulEvents();
    $this->line("Eventful Done!");
    $this->line("Starting Meetup...");
    $this->storeMeetupEvents();
    $this->line("Meetup Done!");
    $this->line("End time: " . (string)date('l jS \of F Y h:i:s A'));
    $this->info("Finished storing events");
  }

  /**
   *
   */
  public static function storeEventbriteEvents()
  {

    Eventbrite::chunk(200, function ($events) {

      foreach ($events as $event) {

        if ($event->logo_url != null) {

          $checkExisting = Happ::where('source_id', '=', $event->eventbriteID);
          $eventRecords = $checkExisting->get();

          if ($eventRecords->count() < 1) {
            $eventRecords->push(new Happ);
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
            $eventRecord->description = clean($event->description_text);
            $eventRecord->all_day_flag = $event->AllDayFlag;
            $eventRecord->event_image_url = $event->logo_url;
            $eventRecord->latitude = $event->latitude;
            $eventRecord->longitude = $event->longitude;

            $venue = Venue::where('source_id', $event->venue_id)->where('source', $eventRecord->source)->first();
            if ($venue) {
              $eventRecord->venue_id = $venue->id;
            }

            if ($event->start_local != null) {
              $eventRecord->event_date = date_format(date_create($event->start_local), "Y-m-d");
              $eventRecord->start_time = date_format(date_create($event->start_local), "Y-m-d H:i:s");
            }

            if ($event->end_local != null) {
              $eventRecord->end_time = date_format(date_create($event->end_local), "Y-m-d H:i:s");
            }

            LoadApiData::keywordFilter($eventRecord);
            if (LoadApiData::$threshold <= $eventRecord->keyword_score) {
              $eventRecord->save();

              foreach ($event->eventbriteCategories as $category) {

                $categoryExisting = EventCategory::where('category_id', '=', $category->category_id)
                  ->where('event_id', '=', $eventRecord->id);

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
    });

  }

  /**
   *
   */
  public static function storeEventfulEvents()
  {
    \DB::connection()->disableQueryLog();
    //$events = Eventful::all();

    Eventful::chunk(200, function ($events) {

      foreach ($events as $event) {

        if ($event->image != null) {

          $checkExisting = Happ::where('source_id', '=', $event->eventful_id);
          $eventRecords = $checkExisting->get();

          if ($eventRecords->count() < 1) {
              $eventRecords->push(new Happ);
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
            $eventRecord->description = clean($event->description);
            $eventRecord->start_time = $event->start_time;
            $eventRecord->end_time = $event->stop_time;
            $eventRecord->all_day_flag = $event->all_day;
            $eventRecord->event_image_url = $event->image;
            $eventRecord->latitude = $event->latitude;
            $eventRecord->longitude = $event->longitude;

            $venue = Venue::where('source_id', $event->venue_id)->where('source', $eventRecord->source)->first();
            if ($venue) {
              $eventRecord->venue_id = $venue->id;
            }

            if ($event->start_time != null) {
              $eventRecord->event_date = date_format(date_create($event->start_time), "Y-m-d");
              $eventRecord->start_time = date_format(date_create($event->start_time), "Y-m-d H:i:s");
            }

            if ($event->stop_time != null) {
              $eventRecord->end_time = date_format(date_create($event->stop_time), "Y-m-d H:i:s");
            }

            LoadApiData::keywordFilter($eventRecord);
            if (LoadApiData::$threshold <= $eventRecord->keyword_score) {
              $eventRecord->save();

              foreach ($event->eventfulCategories as $category) {

                $categoryExisting = EventCategory::where('category_id', '=', $category->category_id)
                  ->where('event_id', '=', $eventRecord->id);
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
    });
  }

  /**
   *
   */
  public static function storeMeetupEvents()
  {

    Meetup::chunk(200, function ($events) {

      foreach ($events as $event) {

        if ($event->photo_url != null) {

          $checkExisting = Happ::where('source_id', '=', $event->meetupID)
            ->where('source', '=', 'Meetup');
          $eventRecords = $checkExisting->get();

          if ($eventRecords->count() < 1) {
            $eventRecords->push(new Happ);
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
            $eventRecord->description = clean($event->description);
            $eventRecord->start_time = $event->time;
            $eventRecord->end_time = ''; // Need to use time, timezone, and duration
            $eventRecord->all_day_flag = $event->all_day;
            $eventRecord->event_image_url = $event->photo_url;
            $eventRecord->latitude = $event->lat;
            $eventRecord->longitude = $event->lon;

            $venue = Venue::where('source_id', '=', $event->venue_id)->where('source', '=', $eventRecord->source)->first();
            if ($venue) {
              $eventRecord->venue_id = $venue->id;
            }

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
                $endSeconds = $seconds + (3 * 60 * 60);
                $eventRecord->end_time = $eventRecord->end_time = date("Y-m-d H:i:s", $endSeconds);
              }
            }

            LoadApiData::keywordFilter($eventRecord);
            if (LoadApiData::$threshold <= $eventRecord->keyword_score) {
              $eventRecord->save();

              foreach ($event->meetupCategories as $category) {

                $categoryExisting = EventCategory::where('category_id', '=', $category->category_id)
                  ->where('event_id', '=', $eventRecord->id);
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
    });
  }

  /**
   *
   */
  public static function keywordFilter(&$eventRecord)
  {
    $file = app_path() . "/../config/filters/keywords.json";
    $keywords = json_decode(file_get_contents($file), true);

    $score = 0;

    // Check for keywords
    foreach ($keywords as $keyword => $value) {
      $score += substr_count(
          strtoupper($eventRecord->event_name),
          strtoupper($keyword)
        ) * $value * 2; // Keywords in the title count double
      $score += substr_count(strtoupper($eventRecord->description), strtoupper($keyword)) * $value;
    }

    $eventRecord->keyword_score = $score;
  }
}
