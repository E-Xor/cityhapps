<?php

namespace CityHapps;

//use Illuminate\Database\Eloquent\Model;
use EchoIt\JsonApi\Model;
use CityHapps\Category;
use CityHapps\AgeLevel;
use CityHapps\Http\Middleware\HappFilter;

class Happ extends Model
{
  protected $table = 'happs';
  protected $guarded = array('id', 'create_at', 'updated_at');
  protected $appends = array('google_directions_link', 'google_map_large', 'similar');

  /**
   * @var int status codes for event states
   */
  const STATUS_DRAFT = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_CANCELLED = 2;
  const STATUS_ARCHIVED = 3;
  const STATUS_DUPLICATED = 4;

  public function users()
  {
    return $this->belongsToMany('User', 'user_event', 'event_id', 'user_id');
  }

  public function categories()
  {
    return $this->belongsToMany('CityHapps\Category', 'event_category', 'event_id', 'category_id');
  }

  public function votes()
  {
    return $this->hasMany('UserEvent', 'event_id', 'id');
  }

  public function recommendedVotes()
  {
    return $this->hasMany('UserEvent', 'event_id', 'event_id');
  }

  public function venue()
  {
    return $this->hasOne('Venue', 'id', 'venue_id');
  }

  // public function ageLevel()
  // {
  //   return $this->hasMany('HappAgeLevel', 'id', 'happ_id');
  // }

  public function tags()
  {
    return $this->belongsToMany('CityHapps\Tag');
  }

  public function ageLevels()
  {
    return $this->belongsToMany('CityHapps\AgeLevel', 'happ_age_level');
  }

  public function duplicated()
  {
    $this->hasMany('Happ', 'parent_id');
  }

  /**
   *
   */
  public static function getHapps($date = null, $timeofday = null, $agelevel = null, $type = null, $zip = null, $zipradius = null, $limit = 50) {
    $query = Happ::with('categories')
      ->with('tags')
      ->with('venue')
      ->with('ageLevels');


    if (!is_null($type)) {
      $query->where('type', '=', $type);
    }

    if (!is_null($zip)) {
      $query->where('zip', '=', $zip);
    }

    if (!is_null($date)) {
      HappFilter::filterDate($query, $date);
    }

    if (!is_null($timeofday)) {
      HappFilter::filterTimeOfDay($query, $timeofday);
    }

    if (!is_null($zipradius)) {
      HappFilter::filterZipRadius($query, $zipradius);
    }

    if (!is_null($agelevel)) {
      HappFilter::filterAgeLevel($query, $agelevel);
    }

    $limit = ($limit <= 100) ? $limit : 100;

    $happs = $query->paginate($limit);

    return $happs;
  }

  /**
   *
   */
  public static function getHappById($happId)
  {
    return Happ::with('categories')
      ->with('tags')
      ->with('venue')
      ->where('id', '=', $happId)
      ->get();
  }

  /**
   *
   */
  public static function getFirstHapp($id)
  {
    return Happ::with('categories')
      ->with('tags')
      ->where('id', '=', $id)
      ->first();
  }

  /**
   * Return a list of duplicated events for the current event.
   */
  public function getSimilarAttribute()
  {
    /**
     * @var self::query Eloquent
     */
    $res = [];
    $event = Happ::where('event_name', 'LIKE', "%{$this->event_name}%")
      ->where('id', '<>', $this->id)
      ->where('event_date', $this->event_date)
      ->orderBy('venue_id', 'asc')
      ->get();

    foreach($event as $id => $e) {
      $res[$id]['id'] = $e->id;
      $res[$id]['event_name'] = $e->event_name;
      $res[$id]['venue_name'] = $e->venue_name;
      $res[$id]['event_image_url'] = $e->event_image_url;
      $res[$id]['event_date'] = $e->event_date;
      $res[$id]['parent_id'] = $e->parent_id;
    }

    return $res;
  }

  public function recurringInformation()
  {
    return $this->hasMany('HappRecurring');
  }

  public static function eventCount($startDate)
  {

    if ($startDate != null) {
      $date = new \DateTime($startDate);
    } else {
      $date = new \DateTime();
    }

    return Happ::where('event_date', '=', $date->format('Y-m-d'))->count();
  }

  /**
   *
   */
  public static function clearStaleApi()
  {
    $total_cancelled = 0;
    $total_archived = 0;
    try {

      // Archive old events first
      $total_archived += \DB::table('happs')
        ->orWhere(
          function ($query) {
            $query->whereNotNull('happs.end_time')
              ->where('happs.end_time', '<', date('Y-m-d H:i:s'));
          }
        )
        ->orWhere(
          function ($query) {
            $query->whereNull('happs.end_time')
              ->where('happs.start_time', '<', date('Y-m-d H:i:s'));
          }
        )
        ->update(array('status' => Happ::STATUS_ARCHIVED));

      $source = array(
        'eb' => array('join_column' => 'eventbriteID', 'table' => 'eventbrite'),
        'ef' => array('join_column' => 'eventful_id', 'table' => 'eventful'),
        'me' => array('join_column' => 'meetupID', 'table' => 'meetup')
      );

      // Loop through and mark any items that are 'active' but missing from the API table as cancelled
      foreach ($source as $alias => $integration) {
        // grab count for each table
        $table = $integration['table'];
        $column = $integration['join_column'];
        // Check that there are records in the table before moving forward
        if (\DB::table($table)->count()) {
          $total_cancelled += \DB::table('happs')
            ->leftJoin(
              $table . ' AS ' . $alias,
              function ($join) use ($alias, $column) {
                $join->on('happs.source_id', '=', $alias . '.' . $column);
              }
            )
            ->where('happs.source', '=', $table)
            ->where('happs.status', '=', Happ::STATUS_ACTIVE)
            ->whereNull($alias . '.id')
            ->update(['status' => Happ::STATUS_CANCELLED]);
        }
      }
    } catch (Exception $e) {
      return array("error" => $e->getCode() . ": " . $e->getMessage());
    }

    return array('cancelled' => $total_cancelled, 'archived' => $total_archived);
  }

  public static function selectEvents($eventParams)
  {

    $events = Happ::with('categories')
      ->with('tags')
      ->maxPerDay($eventParams['maxPerDay'])
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
      ->getPage($eventParams['pageSize'], $eventParams['pageCount'], $eventParams['pageShift']);

    if ($eventParams['eventID'] && !$eventParams['eventName']) {
      $events->where('id', '=', $eventParams['eventID']);
    } else {
      if ($eventParams['eventName']) {
        $events->where('id', '<>', $eventParams['eventID']);
      }
    }
    if ($eventParams['eventName']) {
      $events->where('event_name', 'LIKE', "%{$eventParams['eventName']}%");
    }
    if (!empty($eventParams['pageSize'])) {
      $events->where('status', '<>', Happ::STATUS_DUPLICATED)->orWhere(
        function ($query) {
          $query->where('status', '=', Happ::STATUS_ACTIVE);
        }
      );
    }

    return $events->get();

  }

  public static function recommendedEvents($eventParams)
  {

    $userID = $eventParams['userID'];

    if ($userID != null) {

      $events = Happ::with('categories')
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

  public function scopeEventID($query, $eventID)
  {
    if ($eventID != null) {
      return $query->where('id', '=', $eventID);
    } else {
      return $query;
    }
  }

  public function scopeEventName($query, $eventName)
  {
    if ($eventName != null) {
      return $query->where('event_name', 'like', '%' . $eventName . '%');
    } else {
      return $query;
    }
  }

  public function scopeVenueName($query, $venueName)
  {
    if ($venueName != null) {
      return $query->where('venue_name', 'like', '%' . $venueName . '%');
    } else {
      return $query;
    }
  }

  public function scopeVenueAddress($query, $venueAddress)
  {
    if ($venueAddress != null) {
      return $query->where('address', 'like', '%' . $venueAddress . '%');
    } else {
      return $query;
    }
  }

  public function scopeVenueCity($query, $venueCity)
  {
    if ($venueCity != null) {
      return $query->where('city', 'like', '%' . $venueCity . '%');
    } else {
      return $query;
    }
  }

  public function scopeVenueState($query, $venueState)
  {
    if ($venueState != null) {
      return $query->where('state', 'like', '%' . $venueState . '%');
    } else {
      return $query;
    }
  }

  public function scopeVenueZip($query, $venueZip)
  {
    if ($venueZip != null) {
      return $query->where('zip', 'like', '%' . $venueZip . '%');
    } else {
      return $query;
    }
  }

  public function scopeDescription($query, $description)
  {
    if ($description != null) {
      return $query->where('description', 'like', '%' . $description . '%');
    } else {
      return $query;
    }
  }

  public function scopeStartTime($query, $startTime)
  {

    if ($startTime != null) {
      return $query->where('start_time', '>=', $startTime);
    } else {
      return $query;
    }

  }

  public function scopeDateRange($query, $startDate, $endDate)
  {

    if ($startDate != null) {
      $query->where('event_date', '>=', $startDate);
    }

    if ($endDate != null) {
      $query->where('event_date', '<=', $endDate);
    }

    return $query;
  }

  public function scopeImageRequired($query, $imageRequired)
  {
    if ($imageRequired != null) {
      if ($imageRequired == 'yes') {
        // If the image_required parameter was set to "yes", return only events with an image by default
        return $query->whereNotNull('event_image_url');
      }
    }

    return $query; // Stop processing WHERE clause addition and return
  }

  public function scopeWithCategory($query, $categories)
  {
    if ($categories != null) {

      // categories should be an array
      if ((count($categories) > 0) && is_array($categories)) {

        //$categoryIDs = implode(',', $categories);

        return $query->whereHas(
          'categories',
          function ($q) use ($categories) {
              $q->whereIn('category_id', $categories);
          }
        );

      }

    }

    // If no relevant categories were found, return base query
    return $query;
  }

  public function scopeWithUserEvent($query, $userID)
  {
    if ($userID != null) {

      $joined = $query->with(
        array(
          'votes' => function ($q) use ($userID) {
                $q->where('user_id', '=', $userID);
              }
        )
      ); // use User ID?

      return $joined;

    } else {

      return $query;

    }
  }

  public function scopeWithRecommendedUserEvent($query, $userID)
  {
    if ($userID != null) {

      $joined = $query->with(
        array(
          'recommendedVotes' => function ($q) use ($userID) {
                $q->where('user_id', '=', $userID);
              }
        )
      ); // use User ID?

      return $joined;

    } else {

      return $query;

    }
  }

  public function scopeGetPage($query, $pageSize, $pageCount, $pageShift)
  {
    if (($pageSize != null) && ($pageCount != null)) {
      if ($pageSize == 'all') {
        return $query; // returns all records
      } else {
        if ($pageShift != null) {
          return $query->skip(($pageSize * ($pageCount - 1)) + $pageShift)->take($pageSize);
        } else {
          return $query->skip($pageSize * ($pageCount - 1))->take($pageSize);
        }
      }
    } elseif ($pageSize != null) {
      if ($pageSize == 'all') {
        return $query; // returns all records
      } else {
        return $query->take($pageSize); // Return 50 results by default
      }
    } else {
      return $query->take(50); // Return 50 results by default
    }
  }

  public function scopeMaxPerDay($query, $maxPerDay)
  {
    if ($maxPerDay != null) {
      $topFiveJoin = '(SELECT @position_num := IF(@event_date = event_date, @position_num + 1, 1) AS position, @event_date := event_date AS match_date, e.event_date AS sort_date, e.start_time AS sort_time, e.id AS match_id FROM ' . 'happs' . ' e ORDER BY e.event_date ASC, e.start_time ASC) five_events';

      $eventCountJoin = '(SELECT event_date AS count_date, COUNT(*) AS date_event_count FROM ' . 'happs' . ' GROUP BY event_date ORDER BY event_date) count_events';

      $joined = $query->join(DB::raw($topFiveJoin), 'five_events.match_id', '=', 'happs' . '.id')
        ->join(DB::raw($eventCountJoin), 'count_events.count_date', '=', 'happs' . '.event_date')
        ->where('five_events.position', '<=', $maxPerDay);

      return $joined;
    } else {
      return $query;
    }
  }

  public function scopeEventSearch($query, $search)
  {
    if ($search != null) {

      $searchTerms = explode(' ', $search);

      foreach ($searchTerms as $term) {
        $query->where(
          function ($q) use ($term) {

            $q->orWhere('event_name', 'LIKE', '%' . $term . '%')
              ->orWhere('venue_name', 'LIKE', '%' . $term . '%')
              ->orWhere('address', 'LIKE', '%' . $term . '%')
              ->orWhere('city', 'LIKE', '%' . $term . '%')
              ->orWhere('state', 'LIKE', '%' . $term . '%')
              ->orWhere('zip', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%')

              ->orWhereHas(
                'categories',
                function ($c) use ($term) {
                  $c->where('name', 'LIKE', '%' . $term . '%');
                }
              );
          }
        );
      }
    }

    return $query;
  }

  public function scopeUserCategory($query, $userID)
  {

    $joined = $query->join('event_category as ec', 'happs' . '.id', '=', 'ec.event_id')
      ->join('user_categories as uc', 'uc.category_id', '=', 'ec.category_id')
      ->where('uc.user_id', '=', $userID);

    return $joined;
  }

  public static function storeEvents()
  {
    Happ::storeEventbriteEvents();
    Happ::storeEventfulEvents();
    Happ::storeMeetupEvents();
  }

  public static function storeEventbriteEvents()
  {

    \Eventbrite::chunk(200, function ($events) {

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

            if (Happ::keywordFilter($eventRecord)) {
              $eventRecord->save();

              foreach ($event->eventbriteCategories as $category) {

                $categoryExisting = \EventCategory::where(
                  'category_id',
                  '=',
                  $category->category_id
                )->where('event_id', '=', $eventRecord->id);
                $categoryRecords = $categoryExisting->get();

                if ($categoryRecords->count() < 1) {
                  $categoryRecords->push(new \EventCategory);
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
   * Returns records with the predefined status only
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param int $status
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeStatus($query, $status = 1)
  {
    return $query->where('status', $status);
  }

  public static function storeEventfulEvents()
  {
    \DB::connection()->disableQueryLog();
    //$events = Eventful::all();

    \Eventful::chunk(200, function ($events) {

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
            $eventRecord->description = $event->description;
            $eventRecord->start_time = $event->start_time;
            $eventRecord->end_time = $event->stop_time;
            $eventRecord->all_day_flag = $event->all_day;
            $eventRecord->event_image_url = $event->image;
            $eventRecord->latitude = $event->latitude;
            $eventRecord->longitude = $event->longitude;

            $venue = \Venue::where('source_id', $event->venue_id)->where('source', $eventRecord->source)->first();
            if ($venue) {
              $eventRecord->venue_id = $venue->id;
            }

            $eventRecord->venue_id = $event->venue_id;

            if ($event->start_time != null) {
              $eventRecord->event_date = date_format(date_create($event->start_time), "Y-m-d");
              $eventRecord->start_time = date_format(date_create($event->start_time), "Y-m-d H:i:s");
            }

            if ($event->stop_time != null) {
              $eventRecord->end_time = date_format(date_create($event->stop_time), "Y-m-d H:i:s");
            }

            if (Happ::keywordFilter($eventRecord)) {
              $eventRecord->save();

              foreach ($event->eventfulCategories as $category) {

                $categoryExisting = \EventCategory::where(
                  'category_id',
                  '=',
                  $category->category_id
                )->where('event_id', '=', $eventRecord->id);
                $categoryRecords = $categoryExisting->get();

                if ($categoryRecords->count() < 1) {
                  $categoryRecords->push(new \EventCategory);
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


  public static function storeMeetupEvents()
  {

    \Meetup::chunk(200, function ($events) {

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
            $eventRecord->description = $event->description;
            $eventRecord->start_time = $event->time;
            $eventRecord->end_time = ''; // Need to use time, timezone, and duration
            $eventRecord->all_day_flag = $event->all_day;
            $eventRecord->event_image_url = $event->photo_url;
            $eventRecord->latitude = $event->lat;
            $eventRecord->longitude = $event->lon;

            $venue = \Venue::where('source_id', '=', $event->venue_id)->where('source', '=', $eventRecord->source)->first();
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
            if (Happ::keywordFilter($eventRecord)) {
              $eventRecord->save();

              foreach ($event->meetupCategories as $category) {

                $categoryExisting = \EventCategory::where(
                  'category_id',
                  '=',
                  $category->category_id
                )->where('event_id', '=', $eventRecord->id);
                $categoryRecords = $categoryExisting->get();

                if ($categoryRecords->count() < 1) {
                  $categoryRecords->push(new \EventCategory);
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

  public static function keywordFilter($eventRecord)
  {
    $threshold = 20;

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

    return $score >= $threshold;
  }

  public function getGoogleDirectionsLinkAttribute()
  {
    return 'https://www.google.com/maps/dir/Current+Location/' . $this->latitude . ',' . $this->longitude;
  }

  public function getGoogleMapLargeAttribute()
  {
    return 'http://maps.googleapis.com/maps/api/staticmap?center=' . $this->latitude . ',' . $this->longitude . '&zoom=13&scale=2&size=640x320&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:1%7C' . $this->latitude . ',' . $this->longitude;
  }
}
