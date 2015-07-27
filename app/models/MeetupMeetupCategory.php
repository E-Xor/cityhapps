<?php

use Illuminate\Database\Eloquent\Model;

class MeetupMeetupCategory extends Model
{
	// public $timestamps = false;

	protected $fillable = ['meetup_id', 'meetupCategories_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meetup_meetupCategories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}