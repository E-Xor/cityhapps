<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Active extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	protected $table = 'active';

	public function activeCategories()
	{
		return $this->belongsToMany('ActiveCategory', 'active_activeCategories', 'active_id', 'activeCategories_id');
	}

}