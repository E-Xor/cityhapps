<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;

class SharedEventController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return SharedEvent::all();
	}


	public function testShare() {
		/*
		$share = new SharedEvent;
		$share->event_id = '7019';
		$share->user_id = '6';
		$share->share_link_key = 'link6';
		$share->share_target_platform = 'Facebook';
		$share->save();
		*/
	}


	public function sharedEventPosition($id) {
//		echo $id;

		$shareLinkKey = $id;
		$position = SharedEvent::getEventPosition($shareLinkKey);
		
		return json_encode($position);

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$sharedEvent = Input::get('sharedEvent');

        $sharedEventRecord = new SharedEvent;

        $sharedEventRecord->user_id = $sharedEvent['user_id'];
        $sharedEventRecord->event_id = $sharedEvent['event_id'];
        $sharedEventRecord->share_link_key = $sharedEvent['text'];
        $sharedEventRecord->share_target_platform = $sharedEvnet['target'];

        $sharedEventRecord->save();


        return "Posted a Shared Event";



	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		
	}



	/**
	 * Display the specified resource.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
