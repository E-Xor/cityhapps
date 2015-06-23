<?php

class AdminVenueController extends \BaseController {

  /**
   * Display a listing of the resource.
   * GET /admin/venue
   *
   * @return Response
   */
  public function index()
  {
    //show the admin frontend view
    return View::make('admin/events/home');
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/venue/add
   *
   * @return Response
   */
  public function add()
  {
    // will be shown by angular
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/venue/edit
   *
   * @return Response
   */
  public function edit()
  {
    // will be shown by angular
  }

  public function venues() {

    $venueParams = array();

    $venueParams['venueID'] = Input::get('id');
    $venueParams['venueName'] = Input::get('name');
    $venueParams['url'] = Input::get('url');
    $venueParams['address_1'] = Input::get('venue_address');
    $venueParams['address_2'] = Input::get('venue_address2');
    $venueParams['address_3'] = Input::get('venue_address3');
    $venueParams['city'] = Input::get('venue_city');
    $venueParams['state'] = Input::get('venue_state');
    $venueParams['postal_code'] = Input::get('venue_zip');
    $venueParams['description'] = Input::get('description');

    $venueParams['lat'] = Input::get('latitude');
    $venueParams['lng'] = Input::get('longitude');
    $venueParams['category'] = Input::get('category'); // Note that this will be an array of values called category[]
    $venueParams['createdAt'] = Input::get('created_at');
    $venueParams['updatedAt'] = Input::get('updated_at');
    $venueParams['source'] = Input::get('source');
    $venueParams['image'] = Input::get('image');
    $venueParams['pageSize'] = Input::get('page_size');
    $venueParams['pageCount'] = Input::get('page_count');
    $venueParams['pageShift'] = Input::get('page_shift');
    $venueParams['maxPerDay'] = Input::get('max_per_day');

    $venues = VenueRecord::selectVenues($venueParams);

    $results = array("venues" => $venues);

    return json_encode($results);
  }


  /**
   * Show the form for creating a new resource.
   * POST /admin/venue/update
   *
   * @return Response
   */
  public function update()
  {

    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to update venue';
    $venueParams = array();
    $venueParams = array();

    $venueParams['id'] = Input::get('venue_id');
    if (!$venueParams['id']) $passValidation = false;

    $venueParams['venue_name'] = Input::get('title');
    $venueParams['url'] = Input::get('venue_url');
    $venueParams['venue_name'] = Input::get('venue_name');
    $venueParams['venue_url'] = Input::get('venue_url');
    $venueParams['address'] = Input::get('street_address');
    $venueParams['venue_image_url'] = Input::get('venue_image_url');
   // no room for building
   //$venueParams['building'] = Input::get('building');
    $venueParams['city'] = Input::get('city');
    $venueParams['state'] = Input::get('state');
    $venueParams['zip'] = Input::get('zip_code');
    $venueParams['description'] = Input::get('desc');

   if ($passValidation)
   {
     $result = VenueRecord::find($venueParams['id']);

     if ($result) {
      // then update
      $difference = json_encode(array_keys(array_diff($venueParams, $result->getAttributes())));
      $venueParams['serialized'] = $difference;
      $result->update($venueParams);
      $result['updated'] = 1;
     }
   }

   if ($result)
     return json_encode($result);
   else
     return json_encode(array('error' => true, 'message'=>$message));

 }
  /**
   * Show the form for creating a new resource.
   * POST /admin/venue/create
   *
   * @return Response
   */
  public function create()
  {
    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to create venue';
    $venueParams = array();

    $venueParams['venue_name'] = Input::get('title');
    $venueParams['url'] = Input::get('venue_url');
    $venueParams['venue_name'] = Input::get('venue_name');
    $venueParams['venue_url'] = Input::get('venue_url');
    $venueParams['address'] = Input::get('street_address');
    $venueParams['venue_image_url'] = Input::get('venue_image_url');
    // no room for building
    //$venueParams['building'] = Input::get('building');
    $venueParams['city'] = Input::get('city');
    $venueParams['state'] = Input::get('state');
    $venueParams['zip'] = Input::get('zip_code');
    $venueParams['description'] = Input::get('desc');
    /* just to explain this ternary operator a little bit
    ** sets $time to unix time (if it is an invalid input, null, or not a date, it will return false)
    ** $time is false: return null
    ** $time is satisfactory: return date that mysql can use
    */
    $venueParams['venue_date'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d", $time));
    $venueParams['start_time'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d H:i:s", $time));
    $venueParams['all_day_flag'] = Input::get('all_day');
    $venueParams['end_time'] = (($time = strtotime(Input::get('end_time'))) === false ? null : date("Y-m-d H:i:s", $time));

    $time = strtotime(Input::get('start_time'));
    $start_time = date("Y-m-d H:j:s", $time);
    // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $venueParams['tags'] = Input::get('tags');
    $venueParams['source'] = "Custom";


    if ($passValidation)
      $result = VenueRecord::create($venueParams);

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>$message));

  }

  /**
   * Display a listing of the resource.
   * GET /admin/venue/list
   *
   * @return Response
   */
  public function listVenues()
  {
    //show the admin venue add view
    return View::make('admin/venues/list');
  }

}
