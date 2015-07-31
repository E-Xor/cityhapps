<?php

use Illuminate\Database\Eloquent\Model;

class EventfulEventfulCategory extends Model
{
	// public $timestamps = false;

	protected $fillable = ['eventful_id', 'eventfulCategories_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventful_eventfulCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}