<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserVenue extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// public $timestamps = false;

	protected $fillable = ['user_id', 'venue_id', 'vote'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_venue';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

	public static function storeUserVenueVote($venueParams)
    {
		
		$userId = $venueParams['userId'];
		$venueId = $venueParams['venueId'];
		$vote = $venueParams['vote'];
		
		if (($userId != null) && ($venueId != null)) {


			UserVenue::where('venue_id', '=', $venueId)
						->where('user_id', '=', $userId)
						->delete();

			if ($vote != '-1') {

				$userVenue = new UserVenue;
			
				$userVenue->user_id = $userId;
				$userVenue->venue_id = $venueId;
				$userVenue->vote = $vote;

				$userVenue->save();

				return $userVenue;
			}
		}

		return NULL;

	}

    public static function userVenueVoteStatus($venueParameters)
    {
        $userId = $venueParameters['userId'];
        $venueId = $venueParameters['venueId'];

        if (($userId != null) && ($venueId != null)) {

            $userVenueId = UserVenue::where('venue_id', '=', $venueId)
                ->where('user_id', '=', $userId)
                ->pluck('id');

            if(!is_null($userVenueId)) {
                return ['status' => 'ok'];
            }

            return ['status' => 'not exists'];
        }

        return ['status' => 'error'];
    }

}
