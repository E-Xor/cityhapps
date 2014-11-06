<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('admin', function() {

	return Auth::user();

})->before('auth');


Route::get('/', 'HomeController@showWelcome');

Route::get('harness', 'HarnessController@showHarness');

App::missing(function($exception) {

	return View::make('home');

});

Route::get('testEvents', 'EventController@testEvents'); // TODO: Kill this one in production


Route::get('events', 'EventController@events');
Route::get('eventsPaged', 'EventController@eventsPaged');

Route::post('geoEvents', 'EventController@geoEvents');

Route::get('eventfulEvents', 'EventfulController@events');
Route::get('activeEvents', 'ActiveController@events');
Route::get('meetupEvents', 'MeetupController@events');
Route::get('eventbriteEvents', 'EventbriteController@events');

Route::get('storeEvents', 'EventController@storeEvents');
Route::get('storeEventfulEvents', 'EventfulController@storeEvents');
Route::get('storeActiveEvents', 'ActiveController@storeEvents');
Route::get('storeMeetupEvents', 'MeetupController@storeEvents');
Route::get('storeEventbriteEvents', 'EventbriteController@storeEvents');

Route::post('user_event', 'UserEventController@store');

	Route::get('auth/status', 'SessionsController@index');

	Route::post('auth/login', 'SessionsController@login');

	Route::get('auth/logout', 'SessionsController@logout');

	Route::resource('user', 'UserController');

	Route::post('user/check', 'UserController@check');

	Route::resource('category', 'CategoryController');

	Route::post('auth/login-fb', 'SessionsController@fbNewLogin');



// Route::get("")