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
	protected $table = 'user_event';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

	public static function storeUserVenueVote($eventParams) {
		
		$userID = $eventParams['userID'];
		$venueID = $eventParams['venueID'];
		$vote = $eventParams['vote'];
		
		if (($userID != null) && ($venueID != null)) {


			UserVenue::where('venue_id', '=', $venueID)
						->where('user_id', '=', $userID)
						->delete();

			if ($vote != '-1') {

				$userVenue = new UserVenue;
			
				$userVenue->user_id = $userID;
				$userVenue->venue_id = $venueID;
				$userVenue->vote = $vote;

				$userVenue->save();

				return $userVenue;
			}
		}

		return NULL;

	}

}
