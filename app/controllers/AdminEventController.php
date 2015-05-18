<?php

class AdminEventController extends \BaseController {

  /**
   * Display a listing of the resource.
   * GET /admin/event
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
   * GET /admin/event/add
   *
   * @return Response
   */
  public function add()
  {
    //show the admin event add view
    return View::make('admin/events/add');
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
    // no room for building
    //$eventParams['building'] = Input::get('building');
    $eventParams['city'] = Input::get('city');
    $eventParams['state'] = Input::get('state');
    $eventParams['zip'] = Input::get('zip_code');
    $eventParams['description'] = Input::get('desc');
    $eventParams['event_date'] = Input::get('start_time');
    $eventParams['start_time'] = Input::get('start_time');
    $eventParams['all_day_flag'] = Input::get('all_day');
    $eventParams['end_time'] = Input::get('end_time');
    if (!$eventParams['end_time']) $eventParams['end_time'] = NULL;
    // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $eventParams['tags'] = Input::get('tags');
    $eventParams['source'] = "Custom";


    if ($passValidation)
      $result = EventRecord::create($eventParams);

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>$message));

  }

  /**
   * Display a listing of the resource.
   * GET /admin/event/list
   *
   * @return Response
   */
  public function listEvents()
  {
    // show the admin frontend view
    $events = DB::table('events')
      ->select('id', 'url', 'event_name', 'source', 'event_date', 'start_time', 'end_time')
      ->orderBy('event_date', 'asc')
      ->get();
    $eventCount = DB::table('events')->count();
    $html = '<h1>' . $eventCount . ' Events</h1>
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
      <tr>
      <td colspan=6>
      <table cellspacing="0" cellpadding="1" border="1" style="width:100%">
      <tr>
      <th style="width:32px;">ID</th>
      <th>Title & Link</th>
      <th style="width:67px;">Source</th>
      <th style="width:80px;">Event Date</th>
      <th style="width:150px;">Start Time</th>
      <th style="width:150px;">End Time</th>
      </tr>
      </table>
      </td>
      </tr>
      <tr>
      <td>
      <div style="width:100%; height:600px; overflow:auto;">
      <table cellspacing="0" cellpadding="1" border="1" style="width:100%">';
    foreach ($events as $event)
    {
      $html .= '<tr>';
      $html .= '<td style="width:32px;">' . $event->id . '</td>';
      $html .= '<td><a href="' . $event->url . '" target="_new">' . $event->event_name . '</a></td>';
      $html .= '<td style="width:67px;">' . $event->source . '</td>';
      $html .= '<td style="width:80px;">' . $event->event_date . '</td>';
      $html .= '<td style="width:150px;">' . $event->start_time . '</td>';
      $html .= '<td style="width:150px;">' . $event->end_time . '</td>';
    }
    $html .= "</table>
      </div>
      </td>
      </tr>
      </table>";
    return $html;
  }

}
