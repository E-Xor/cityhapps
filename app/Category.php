<?php

namespace CityHapps;

//use Illuminate\Database\Eloquent\Model;
use EchoIt\JsonApi\Model;

class Category extends Model {

	// public $timestamps = false;

	protected $fillable = ['name'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

	public function users()
	{
		return $this->belongsToMany('CityHapps\User', 'user_categories', 'category_id', 'user_id');
	}

	public function happs()
	{
		return $this->belongsToMany('CityHapps\Happ', 'event_category', 'category_id', 'event_id');
	}

}
