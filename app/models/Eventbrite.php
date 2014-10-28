<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Eventbrite extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventbrite';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = '';

}