<?php

class VenueRecord extends Eloquent
{

    protected $guarded = array('id', 'create_at', 'updated_at');

    protected $table = 'venues';

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

    public static function storeVenues()
    {
        VenueRecord::storeFoursquareVenues();
        VenueRecord::storeEventfulVenues();
        VenueRecord::storeMeetupVenues();
    }

    public static function storeFoursquareVenues()
    {
        FoursquareVenues::chunk(
            200,
            function ($venues) {
                foreach ($venues as $venue) {

                    $checkExisting = VenueRecord::where('source_id', '=', $venue->foursquareVenueID);
                    $venueRecords = $checkExisting->get();

                    if ($venueRecords->count() < 1) {
                        $venueRecords->push(new VenueRecord);
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

    public static function storeEventfulVenues()
    {
        EventfulVenues::chunk(
            200,
            function ($venues) {
                foreach ($venues as $venue) {

                    $checkExisting = VenueRecord::where('source_id', '=', $venue->eventfulVenueID);
                    $venueRecords = $checkExisting->get();

                    if ($venueRecords->count() < 1) {
                        $venueRecords->push(new VenueRecord);
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

  /** $venueParams will be a way to minimize results, for now it is not in use */
  public static function selectVenues($venueParams) {

    $venues = VenueRecord::get();

    return $venues;

  }

   public static function venueCount()
    {

      return VenueRecord::count();
    }

    public static function storeMeetupVenues()
    {

        MeetupVenues::chunk(
            200,
            function ($venues) {
                foreach ($venues as $venue) {
                    $checkExisting = VenueRecord::where('source_id', '=', $venue->meetupVenueID);
                    $venueRecords = $checkExisting->get();

                    if ($venueRecords->count() < 1) {
                        $venueRecords->push(new VenueRecord);
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
}
