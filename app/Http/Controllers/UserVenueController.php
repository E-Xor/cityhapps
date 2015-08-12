<?php

namespace CityHapps\Http\Controllers;

use Illuminate\Http\Request;

use CityHapps\Http\Requests;
use CityHapps\Http\Controllers\Controller;

class UserVenueController extends Controller {

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