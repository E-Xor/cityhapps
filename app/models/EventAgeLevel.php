<?php

class EventAgeLevel extends \Eloquent {
	protected $fillable = [];
	protected $table = 'event_age_level';

	function event(){
		return $this->belongsTo('EventRecord');
	}
}
