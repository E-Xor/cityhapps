<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Eventful extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventful';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = '';

}