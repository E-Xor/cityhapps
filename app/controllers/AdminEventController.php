<?php

class AdminEventController extends \BaseController {

  /**
   * Show the form for creating a new resource.
   * GET /admin/event/add
   *
   * @return Response
   */
  public function add()
  {
    // will be shown by angular
  }

  /**
   * Show the form for creating a new resource.
   * GET /admin/event/edit
   *
   * @return Response
   */
  public function edit()
  {
    // will be shown by angular
  }
  /**
   * Show the form for creating a new resource.
   * POST /admin/event/update
   *
   * @return Response
   */
  public function update()
  {

    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to update event';
    $eventParams = array();
    $eventParams = array();

    $eventParams['id'] = Input::get('event_id');
    if (!$eventParams['id']) $passValidation = false;

    $eventParams['event_name'] = Input::get('title');
    $eventParams['url'] = Input::get('event_url');
    $eventParams['venue_name'] = Input::get('venue_name');
    $eventParams['venue_url'] = Input::get('venue_url');
    $eventParams['address'] = Input::get('street_address');
    $eventParams['event_image_url'] = Input::get('event_image_url');
   // no room for building
   //$eventParams['building'] = Input::get('building');
    $eventParams['city'] = Input::get('city');
    $eventParams['state'] = Input::get('state');
    $eventParams['zip'] = Input::get('zip_code');
    $eventParams['description'] = Input::get('desc');
   /* just to explain this ternary operator a little bit
   ** sets $time to unix time (if it is an invalid input, null, or not a date, it will return false)
   ** $time is false: return null
   ** $time is satisfactory: return date that mysql can use
   */
   $eventParams['event_date'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d", $time));
   $eventParams['start_time'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d H:i:s", $time));
   $eventParams['all_day_flag'] = Input::get('all_day');
   $eventParams['end_time'] = (($time = strtotime(Input::get('end_time'))) === false ? null : date("Y-m-d H:i:s", $time));

   $time = strtotime(Input::get('start_time'));
   $start_time = date("Y-m-d H:j:s", $time);
   // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $eventParams['tags'] = Input::get('tags');

   if ($passValidation)
   {
     $result = Happ::find($eventParams['id']);

     if ($result) {
      // then update
      $difference = json_encode(array_keys(array_diff($eventParams, $result->getAttributes())));
      $eventParams['serialized'] = $difference;
      $result->update($eventParams);
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
   * POST /admin/event/create
   *
   * @return Response
   */
  public function create()
  {
    // logic to push to model includes database transactions, sanitizing, etc.
    // fall back error message
    $passValidation = true;
    $message = 'Failed to create event';
    $eventParams = array();

    $eventParams['event_name'] = Input::get('title');
    $eventParams['url'] = Input::get('event_url');
    $eventParams['venue_name'] = Input::get('venue_name');
    $eventParams['venue_url'] = Input::get('venue_url');
    $eventParams['address'] = Input::get('street_address');
    $eventParams['event_image_url'] = Input::get('event_image_url');
    // no room for building
    //$eventParams['building'] = Input::get('building');
    $eventParams['city'] = Input::get('city');
    $eventParams['state'] = Input::get('state');
    $eventParams['zip'] = Input::get('zip_code');
    $eventParams['description'] = Input::get('desc');
    /* just to explain this ternary operator a little bit
    ** sets $time to unix time (if it is an invalid input, null, or not a date, it will return false)
    ** $time is false: return null
    ** $time is satisfactory: return date that mysql can use
    */
    $eventParams['event_date'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d", $time));
    $eventParams['start_time'] = (($time = strtotime(Input::get('start_time'))) === false ? null : date("Y-m-d H:i:s", $time));
    $eventParams['all_day_flag'] = Input::get('all_day');
    $eventParams['end_time'] = (($time = strtotime(Input::get('end_time'))) === false ? null : date("Y-m-d H:i:s", $time));

    $time = strtotime(Input::get('start_time'));
    $start_time = date("Y-m-d H:j:s", $time);
    // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $eventParams['tags'] = Input::get('tags');
    $eventParams['source'] = "Custom";


    if ($passValidation)
      $result = Happ::create($eventParams);

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>$message));

  }

}
