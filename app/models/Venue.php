<?php

class Venue extends Eloquent
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

    $query = $buildQuery . " WHERE " . $buildWhere;
    $queryResult = DB::delete($query);

    return $queryResult;
  }

  /**
   *
   */
  public static function storeVenues()
  {
    Venue::storeFoursquareVenues();
    Venue::storeEventfulVenues();
    Venue::storeMeetupVenues();
  }

  /** 
   * $venueParams will be a way to minimize results, for now it is not in use
   */
  public static function selectVenues($params) {
    $venues = Venue::paginate($params['pageSize']);
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
