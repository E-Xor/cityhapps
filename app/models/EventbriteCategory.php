<?php

use Illuminate\Database\Eloquent\Model;

class EventbriteCategory extends Model {

	// public $timestamps = false;

	protected $fillable = ['category_id', 'name', 'long_name', 'source_category_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventbriteCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}