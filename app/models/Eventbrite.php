<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Eventbrite extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'eventbrite';

	public function eventbriteCategories()
	{
		return $this->belongsToMany('EventbriteCategory', 'eventbrite_eventbriteCategories', 'eventbrite_id', 'eventbriteCategories_id');
	}

}