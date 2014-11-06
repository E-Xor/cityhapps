<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Eventful extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'eventful';

	public function eventfulCategories()
	{
		return $this->belongsToMany('EventfulCategory', 'eventful_eventfulCategories', 'eventful_id', 'eventfulCategories_id');
	}

}