<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Meetup extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'meetup';

	public function meetupCategories()
	{
		return $this->belongsToMany('MeetupCategory', 'meetup_meetupCategories', 'meetup_id', 'meetupCategories_id');
	}

}