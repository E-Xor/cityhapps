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
    $eventParams['end_time'] = Input::get('end_time');
    // no spot for tags? (maybe this is keywords, and should get ran through some filtering?)
   // $eventParams['tags'] = Input::get('tags');
    $eventParams['source'] = "Custom";
    $result = EventRecord::create($eventParams);

    if ($result)
      return json_encode($result);
    else
      return json_encode(array('error' => true, 'message'=>'Failed to create event'));

  }

  /**
   * Display a listing of the resource.
   * GET /admin/event/list
   *
   * @return Response
   */
  public function listEvents()
  {
    //show the admin frontend view
    // is it okay to just return html here?
    $events = DB::table('events')->select('id', 'url', 'event_name', 'source', 'event_date', 'start_time', 'end_time')->orderBy('event_date', 'asc')->get();
    $eventCount = DB::table('events')->count();
    $html = '<h1>'.$eventCount.' Events</h1>
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
      <tr>
      <td>
      <table cellspacing="0" cellpadding="1" border="1" style="width:100%">
      <tr>
      <th style="width:32px;">ID</th>
      <th style="width:911px;">Event URL</th>
      <th style="width:67px;">API Source</th>
      <th style="width:53px;">Event Date</th>
      <th>Start Time</th>
      <th>End Time</th>
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
      $html .= "<tr>";
      $html .= "<td>".$event->id."</td>";
      $html .= "<td><a href='".$event->url."'>".$event->event_name."</a></td>";
      $html .= "<td>".$event->source."</td>";
      $html .= "<td>".$event->event_date."</td>";
      $html .= "<td>".$event->start_time."</td>";
      $html .= "<td>".$event->end_time."</td>";
    }
    $html .= " </table>
      </div>
      </td>
      </tr>
      </table>";
    return $html;
  }

}
