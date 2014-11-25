<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserEvent extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// public $timestamps = false;

	protected $fillable = ['user_id', 'event_id', 'vote'];

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

	public static function storeUserEventVote($eventParams) {
		
		$userID = $eventParams['userID'];
		$eventID = $eventParams['eventID'];
		$vote = $eventParams['vote'];
		
		if (($userID != null) && ($eventID != null)) {

			// Delete any existing UserEvent records for this user and event combo
			UserEvent::where('event_id', '=', $eventID)
						->where('user_id', '=', $userID)
						->delete();

			if ($vote != '-1') {

				$userEvent = new UserEvent;
			
				$userEvent->user_id = $userID;
				$userEvent->event_id = $eventID;
				$userEvent->vote = $vote;	

				$userEvent->save();

				return $userEvent;
			}
		}

		return NULL;

	}

}