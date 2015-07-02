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


Route::get('/', 'AdminEventController@listEvents');

Route::get('harness', 'HarnessController@showHarness');

App::missing(function($exception) {

  return View::make('home');

});

// Route::get('share/{id}', 'SharedEventController@sharedEventPosition' );

Route::get('testEvents', 'EventController@testEvents'); // TODO: Kill this one in production
Route::get('testShare', 'SharedEventController@testShare'); // TODO: Kill this one in production

Route::get('sharedEventPosition', 'SharedEventController@sharedEventPosition');
Route::resource('sharedEvent', 'SharedEventController');


Route::get('events', 'EventController@events');
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

Route::get('auth/status', 'SessionsController@index');

Route::post('auth/login', 'SessionsController@login');

Route::get('auth/logout', 'SessionsController@logout');

Route::resource('user', 'UserController');

Route::post('user/check', 'UserController@check');
Route::post('user/exist', 'UserController@exist');
Route::post('user/reset-password', 'UserController@resetPassword');
Route::post('user/username', 'UserController@getUserName');
Route::post('user/edit', 'UserController@editUser');

Route::post('updateUserCategories', 'UserController@updateCategories');
Route::get('getUserCategories', 'UserController@getUserCategories');

Route::resource('category', 'CategoryController');

Route::post('auth/login-fb', 'SessionsController@fbNewLogin');

Route::get('admin', function() {

  //old admin return
  Auth::user();
});

Route::get('admin/event', 'AdminEventController@index');
Route::get('admin/event/list', 'HomeController@showWelcome');
Route::post('admin/event/create', 'AdminEventController@create');
Route::post('admin/event/update', 'AdminEventController@update');

// Route::get("")
