<?php

class UserEventController extends \BaseController {

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
		$user_id = Input::get('user_id');
		$event_id = Input::get('event_id');
		$vote = Input::get('vote');
		
		$findUserEvent = UserEvent::where('event_id', '=', $event_id)->first();

		if ($findUserEvent) {
			$findUserEvent->vote = $vote;

			$findUserEvent->save();

			return $findUserEvent;

		} else {
			$userEvent = new UserEvent;
		
			$userEvent->user_id = $user_id;
			$userEvent->event_id = $event_id;
			$userEvent->vote = $vote;	

			$userEvent->save();

			return $userEvent;
		}

		// if ($userEvent->upvote == null) {
		// 	$userEvent->upvote = false;
		// } else {
		// 	$userEvent->upvote = $upVote;
		// }

		// if ($userEvent->downvote == null){
		// 	$userEvent->downvote = false; 			
		// } else {
		// 	$userEvent->downvote = $downVote;	
		// }

		

		

		
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