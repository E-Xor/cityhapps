<?php

use Illuminate\Database\Eloquent\Model;

class EventbriteEventbriteCategory extends Model
{
	// public $timestamps = false;

	protected $fillable = ['eventbrite_id', 'eventbriteCategories_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventbrite_eventbriteCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}