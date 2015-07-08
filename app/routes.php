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


Route::get('/', 'HomeController@index');

App::missing(function($exception) {
  return View::make('main');
});

Route::get('harness', 'HarnessController@showHarness');

// Route::get('share/{id}', 'SharedEventController@sharedEventPosition' );

Route::get('testEvents', 'EventController@testEvents'); // TODO: Kill this one in production
Route::get('testShare', 'SharedEventController@testShare'); // TODO: Kill this one in production

Route::get('sharedEventPosition', 'SharedEventController@sharedEventPosition');
Route::resource('sharedEvent', 'SharedEventController');


Route::get('events', 'EventController@events');
Route::get('venues', 'VenueController@venues');
Route::get('recommendedEvents', 'EventController@recommendedEvents');
Route::get('eventsPaged', 'EventController@eventsPaged');
Route::post('dayEvents', 'EventController@dayEvents');
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

Route::post('userEvent', 'UserEventController@store');
Route::post('checkUserEventVote', 'UserEventController@checkLikeStatus');

Route::post('userVenue', 'UserVenueController@store');
Route::post('checkUserVenueVote', 'UserVenueController@checkLikeStatus');

Route::get('auth/status', 'SessionsController@index');

Route::post('auth/login', 'SessionsController@login');

Route::get('auth/logout', 'SessionsController@logout');

Route::resource('user', 'UserController');

Route::post('user/check', 'UserController@check');
Route::post('user/exist', 'UserController@exist');
Route::post('user/reset-password', 'UserController@resetPassword');
Route::post('user/getData', 'UserController@getUserData');
Route::post('user/edit', 'UserController@editUser');

Route::post('updateUserCategories', 'UserController@updateCategories');
Route::get('getUserCategories', 'UserController@getUserCategories');

Route::resource('category', 'CategoryController');

Route::post('auth/login-fb', 'SessionsController@fbNewLogin');

/** Admin **/
/** Admin Event **/
Route::get('admin/event/list', 'HomeController@showWelcome');
Route::post('admin/event/create', 'AdminEventController@create');
Route::post('admin/event/update', 'AdminEventController@update');
/** Admin Venue **/
Route::post('admin/venue/create', 'AdminVenueController@create');
Route::post('admin/venue/update', 'AdminVenueController@update');
