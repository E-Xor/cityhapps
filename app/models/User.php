<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// public $timestamps = false;

	protected $fillable = ['email', 'username', 'password'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function categories()
	{
		return $this->belongsToMany('Category', 'user_categories');
	}

	public function events()
	{
		return $this->belongsToMany('Event', 'user_event', 'user_id', 'event_id');
	}

}
