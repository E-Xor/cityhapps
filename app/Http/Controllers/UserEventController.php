<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;

class UserEventController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /user_event
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /user_event/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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

    public function checkLikeStatus()
    {
        $userId = Input::get('user_id');
        $eventId = Input::get('event_id');

        if (($userId != null) && ($eventId != null)) {

            $userEventId = UserEvent::where('event_id', '=', $eventId)
                ->where('user_id', '=', $userId)
                ->pluck('id');

            if(!is_null($userEventId)) {
                return json_encode(['status' => 'ok']);
            }

            return json_encode(['status' => 'not exists']);
        }

        return json_encode(['status' => 'error']);
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