<?php

class VenueController extends BaseController {

	public static function storeVenues()
    {
		Venue::storeVenues();

		return true;
	}


  public function venues() {

    $venueParams = array();

    $venueParams['id'] = Input::get('id');
    $venueParams['eventName'] = Input::get('name');
    $venueParams['venueName'] = Input::get('venue_name');
    $venueParams['venueAddress'] = Input::get('venue_address');
    $venueParams['venueCity'] = Input::get('venue_city');
    $venueParams['venueState'] = Input::get('venue_state');
    $venueParams['venueZip'] = Input::get('venue_zip');
    $venueParams['description'] = Input::get('description');
    $venueParams['startTime'] = Input::get('start_time');
    $venueParams['startDate'] = Input::get('start_date');
    $venueParams['endDate'] = Input::get('end_date');
    $venueParams['latitude'] = Input::get('latitude');
    $venueParams['longitude'] = Input::get('longitude');
    $venueParams['createdAt'] = Input::get('created_at');
    $venueParams['updatedAt'] = Input::get('updated_at');
    $venueParams['source'] = Input::get('source');
    $venueParams['imageRequired'] = Input::get('image_required');
    $venueParams['pageSize'] = Input::get('page_size');
    $venueParams['pageCount'] = Input::get('page_count');
    $venueParams['pageShift'] = Input::get('page_shift');
    $venueParams['maxPerDay'] = Input::get('max_per_day');

    $venues = Venue::selectVenues($venueParams);

    $meta = array();
    $count = Venue::venueCount($venueParams['startDate']);
    $meta["count"] = $count;

    $results = array("meta" => $meta, "venues" => $venues);

    return json_encode($results);
  }


}