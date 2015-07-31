<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SharedEvent extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'sharedEvents';

	public static function getEventPosition($shareLinkKey) {

		$rank = null;

		if ($shareLinkKey != null) {

			$sharedEvents = SharedEvent::where('share_link_key', '=', $shareLinkKey)->first();

			if (count($sharedEvents) > 0) {

				$eventID = $sharedEvents->event_id;

				if ($eventID != null) {

					$events = DB::select('SELECT * FROM (SELECT @rownum := @rownum + 1 AS position, t.* FROM events t, (SELECT @rownum := 0) r ORDER BY t.event_date asc, t.start_time asc) c WHERE c.ID = ?', array($eventID));

					if (count($events) > 0) {

						$rank = $events[0]->position;

					}

				}

			}

		}

		return $rank;

	}

}