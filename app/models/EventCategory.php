<?php

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
	// public $timestamps = false;

	protected $fillable = ['event_id', 'category_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'event_category';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('id');

}