<?php

class UserVenueController extends \BaseController {

	public function store()
	{
        $venueParams = [];

        $venueParams['userId'] = Input::get('user_id');
        $venueParams['venueId'] = Input::get('venue_id');
        $venueParams['vote'] = Input::get('vote');

		return json_encode(UserVenue::storeUserVenueVote($venueParams));
		
	}

    public function checkLikeStatus()
    {
        $venueParams = [];

        $venueParams['userId'] = Input::get('user_id');
        $venueParams['venueId'] = Input::get('venue_id');

        return json_encode(UserVenue::userVenueVoteStatus($venueParams));
    }

}