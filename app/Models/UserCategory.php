<?php


class UserCategory extends \Eloquent {


	// public $timestamps = false;

	protected $fillable = ['user_id', 'category_id'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_categories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('id');

}
