<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EventRecord extends Eloquent {

	// use UserTrait, RemindableTrait;

	// public $timestamps = false;

// 	protected $fillable = [
// 'watching_count',	
// 'olson_path', 
// 'calendar_count' , 
// 'comment_count ',
// 'region_abbr', 		
// 'postal_code',	
// 'going_count',
// 'all_day',
// 'latitude',		
// 'groups',			
// 'url',				
// 'eventful_id', 		
// 'privacy'	
// // city_name		
// // link_count		
// // longitude		
// // country_name	
// // country_abbr	
// // region_name		
// // start_time		
// // tz_id			
// // description		
// // modified		
// // venue_display	
// // tz_country		
// // performers		
// // title			
// // venue_address	
// // geocode_type	
// // tz_olson_path	
// // recur_string	
// // calendars		
// // owner			
// // going			
// // country_abbr2	
// // image			
// // created			
// // venue_id		
// // tz_city			
// // stop_time		
// // venue_name		
// // venue_url		

// ];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}