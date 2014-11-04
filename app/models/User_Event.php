<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User_Event extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// public $timestamps = false;

	protected $fillable = ['user_id', 'event_id'];

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
	protected $hidden = array('id');

}