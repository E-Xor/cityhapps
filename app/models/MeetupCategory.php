<?php

use Illuminate\Database\Eloquent\Model;

class MeetupCategory extends Model
{
	// public $timestamps = false;

	protected $fillable = ['category_id', 'name', 'long_name', 'source_category_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meetupCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}