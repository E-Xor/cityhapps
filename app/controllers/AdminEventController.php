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
  public function create()
  {
    //
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

  /**
   * Store a newly created resource in storage.
   * POST /user_event
   *
   * @return Response
   */
  public function store()
  {
    $eventParams = array();

    $eventParams['userID'] = Input::get('user_id');
    $eventParams['eventID'] = Input::get('event_id');
    $eventParams['vote'] = Input::get('vote');

    $events = UserEvent::storeUserEventVote($eventParams);

    return json_encode($events);

  }

  /**
   * Display the specified resource.
   * GET /user_event/{id}
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   * GET /user_event/{id}/edit
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   * PUT /user_event/{id}
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   * DELETE /user_event/{id}
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }

}
