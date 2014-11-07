<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class ActiveActiveCategory extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// public $timestamps = false;

	protected $fillable = ['active_id', 'activeCategories_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'active_activeCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}