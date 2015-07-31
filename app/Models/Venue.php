<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
  protected $table = 'venues';
  protected $guarded = array('id', 'create_at', 'updated_at');
  protected $appends = array('google_directions_link', 'google_map_large', 'similar');


  /**
   * Return a list of duplicated venues for the current venue.
   */
  public function getSimilarAttribute(){
    /**
     * @var self::query Eloquent
     */
    $res = [];
    $event = Venue::where('name', 'LIKE',  "%{$this->name}%")
                 ->where('id','<>',$this->id)
                 ->orderBy('id', 'asc')
                 ->get();

    foreach($event as $id => $e) {
      $res[$id]['id'] = $e->id;
      $res[$id]['venue_name'] = $e->name;
      $res[$id]['venue_address_1'] = $e->address_1;
      $res[$id]['parent_id'] = $e->parent_id;
      $res[$id]['venue_lat'] = $e->lat;
      $res[$id]['venue_lng'] = $e->lng;
    }

    return $res;
  }

  /**
   *
   */
  public static function clearStaleApi()
  {
    $buildQuery = "DELETE venues FROM venues ";
    $buildWhere = "";

    $fsCount = DB::table('foursquare_venues')->count();
    $efCount = DB::table('eventful_venues')->count();
    $meCount = DB::table('meetup_venues')->count();
    $ebCount = DB::table('eventbrite')->count();

    if ($fsCount != 0) {
      $buildQuery .= "LEFT JOIN foursquare_venues fs ON (fs.foursquareVenueID = venues.source_id AND venues.source = 'Foursquare') ";
      $buildWhere .= ($buildWhere ? "AND fs.id IS NULL " : "fs.id IS NULL ");
    }

    if ($efCount != 0) {
      $buildQuery .= "LEFT JOIN eventful_venues ef ON (ef.eventfulVenueID = venues.source_id AND venues.source = 'Eventful') ";
      $buildWhere .= ($buildWhere ? "AND ef.id IS NULL " : "ef.id IS NULL ");
    }

    if ($meCount != 0) {
      $buildQuery .= "LEFT JOIN meetup_venues m ON (m.meetupVenueID = venues.source_id AND venues.source = 'Meetup') ";
      $buildWhere .= ($buildWhere ? "AND m.id IS NULL " : "m.id IS NULL ");
    }

    //Delete events that are no longer reflected in the eventbrite table
    if ($ebCount){
        $buildQuery .= "LEFT JOIN eventbrite eb ON (eb.venue_id = venues.source_id AND venues.source = 'Eventbrite') ";
        $buildWhere .= ($buildWhere ? "AND eb.venue_id IS NULL " : "eb.venue_id IS NULL ");
    }

    $query = $buildQuery . " WHERE " . $buildWhere;
    $queryResult = DB::delete($query);

    return $queryResult;
  }

  /**
   *
   */
  public static function storeVenues()
  {
    Log::useFiles('php://stdout', 'info');
    Log::info('Processing Foursquare.');
    Venue::storeFoursquareVenues();
    Log::info('Processing Eventful.');
    Venue::storeEventfulVenues();
    Log::info('Processing Meetup.');
    Venue::storeMeetupVenues();
    Log::info('Processing Eventbrite.');
    Venue::storeEventBriteVenues();
  }

  /** 
   * $venueParams will be a way to minimize results, for now it is not in use
   */
  public static function selectVenues($params) {
    $venues = Venue::with('tags')->paginate($params['pageSize']);
    return $venues;
  }

  /**
   *
   */
  public static function storeFoursquareVenues()
  {
    FoursquareVenues::chunk(
      200,
      function ($venues) {
        foreach ($venues as $venue) {

          $checkExisting = Venue::where('source_id', '=', $venue->foursquareVenueID);
          $venueRecords = $checkExisting->get();

          if ($venueRecords->count() < 1) {
            $venueRecords->push(new Venue);
          }

          foreach ($venueRecords as $venueRecord) {
            $venueRecord->source         = 'Foursquare';
            $venueRecord->source_id      = $venue->foursquareVenueID;
            $venueRecord->name           = $venue->name;
            $venueRecord->url            = $venue->url;
            $venueRecord->lat            = $venue->location_lat;
            $venueRecord->lng            = $venue->location_lng;
            $venueRecord->address_1      = $venue->location_address;
            $venueRecord->city           = $venue->location_city;
            $venueRecord->state          = $venue->location_state;
            $venueRecord->postal_code    = $venue->location_postalCode;
            $venueRecord->country        = $venue->location_country;

            try {
              $venueRecord->save();
            } catch (Exception $e) {

            }
          }
        }
      }
    );
  }

  /**
   *
   */
  public static function storeEventfulVenues()
  {
    EventfulVenues::chunk(
      200,
      function ($venues) {
        foreach ($venues as $venue) {

          $checkExisting = Venue::where('source_id', '=', $venue->eventfulVenueID);
          $venueRecords = $checkExisting->get();

          if ($venueRecords->count() < 1) {
            $venueRecords->push(new Venue);
          }

          foreach ($venueRecords as $venueRecord) {
            $venueRecord->source         = 'Eventful';
            $venueRecord->source_id      = $venue->eventfulVenueID;
            $venueRecord->name           = $venue->name;
            $venueRecord->url            = $venue->url;
            $venueRecord->lat            = $venue->latitude;
            $venueRecord->lng            = $venue->longitude;
            $venueRecord->address_1      = $venue->address;
            $venueRecord->city           = $venue->city_name;
            $venueRecord->state          = $venue->region_abbr;
            $venueRecord->postal_code    = $venue->postal_code;
            $venueRecord->country        = $venue->country_name;

            try {
              $venueRecord->save();
            } catch (Exception $e) {

            }
          }
        }
      }
    );
  }

  public function tags()
  {
    return $this->belongsToMany('Tag', 'venue_tag' );
  }

  /**
   * Stores venues data from the main eventbrite table into the consolidated
   * venues table, to speed up searching
   */
    public static function storeEventBriteVenues()
    {

        //Using raw SQL instead of eloquent, as it is like 1000% faster for this alone.
        $insert_select = 'INSERT INTO venues (source_id, source, name, lat, lng, address_1, city, state, postal_code, country) ';
        $insert_select .= 'SELECT DISTINCT(venue_id) AS venue_id, "Eventbrite", venue_name, latitude, longitude, address_1, city, region, postal_code, "us"  FROM eventbrite ';
        $insert_select .= 'ON DUPLICATE KEY UPDATE lat=VALUES(lat), lng=VALUES(lng),city=VALUES(city),address_1=VALUES(address_1), state=VALUES(state), postal_code=VALUES(postal_code)';

        DB::statement($insert_select);

    }

  /**
   *
   */
  public static function storeMeetupVenues()
  {
    MeetupVenues::chunk(
      200,
      function ($venues) {
        foreach ($venues as $venue) {
          $checkExisting = Venue::where('source_id', '=', $venue->meetupVenueID);
          $venueRecords = $checkExisting->get();

          if ($venueRecords->count() < 1) {
            $venueRecords->push(new Venue);
          }

          foreach ($venueRecords as $venueRecord) {
            $venueRecord->source         = 'Meetup';
            $venueRecord->source_id      = $venue->meetupVenueID;
            $venueRecord->name           = $venue->name;
            $venueRecord->url            = $venue->venue_url;
            $venueRecord->lat            = $venue->lat;
            $venueRecord->lng            = $venue->lon;
            $venueRecord->address_1      = $venue->address_1;
            $venueRecord->city           = $venue->city;
            $venueRecord->state          = $venue->state;
            $venueRecord->postal_code    = $venue->zip;
            $venueRecord->country        = $venue->country;

            try {
              $venueRecord->save();
            } catch (Exception $e) {

            }
          }
        }
      }
    );
  }

  public function getGoogleDirectionsLinkAttribute()
  {
    return 'https://www.google.com/maps/dir/Current+Location/' . $this->lat . ',' . $this->lng;
  }

  public function getGoogleMapLargeAttribute()
  {
    return 'http://maps.googleapis.com/maps/api/staticmap?center=' . $this->lat . ',' . $this->lng . '&zoom=13&scale=2&size=640x320&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:1%7C' . $this->lat . ',' . $this->lng;
  }

}
