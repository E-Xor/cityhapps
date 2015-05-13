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
    return "yolo";
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
