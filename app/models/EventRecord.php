<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EventRecord extends Eloquent {

	protected $guarded = array('id','create_at', 'updated_at');

	protected $table = 'events';

	public function users()
	{
		return $this->belongsToMany('User', 'user_event', 'event_id', 'user_id');
	}

	public function categories()
	{
		return $this->belongsToMany('Category', 'event_category', 'event_id', 'category_id');
	}

}