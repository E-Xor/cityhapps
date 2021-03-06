<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('preview', 'HomeController@preview');

Route::get('harness', 'HarnessController@showHarness');

// Route::get('share/{id}', 'SharedEventController@sharedEventPosition' );

Route::get('testEvents', 'EventController@testEvents'); // TODO: Kill this one in production
Route::get('testShare', 'SharedEventController@testShare'); // TODO: Kill this one in production

Route::get('sharedEventPosition', 'SharedEventController@sharedEventPosition');
Route::resource('sharedEvent', 'SharedEventController');

Route::get('happs{date?}{timeofday?}{agelevel?}{type?}{zip?}{zipradius?}{limit?}', 'HappController@getHappList');
Route::get('happs/{id}', 'HappController@getHapp');
Route::get('happs/{id}/venue', 'HappController@getHappVenue');

Route::get('events', 'EventController@events');
//Route::get('venues', 'VenueController@venues');
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
Route::post('user/change-password', 'UserController@changePassword');
Route::post('user/getData', 'UserController@getUserData');

Route::post('updateUserCategories', 'UserController@updateCategories');
Route::get('getUserCategories', 'UserController@getUserCategories');

Route::post('auth/login-fb', 'SessionsController@fbNewLogin');

Route::get('social_auth/facebook/login', 'SessionsController@FacebookLogin');
Route::get('social_auth/facebook/callback', 'SessionsController@FacebookCallback');
Route::get('social_auth/google/login', 'SessionsController@GoogleLogin');
Route::get('social_auth/google/callback', 'SessionsController@GoogleCallback');


Route::get('tags/{name}','TagController@getTags');

// Admin Event

Route::post('admin/event/create', 'AdminEventController@create');
Route::post('admin/event/update', 'AdminEventController@update');
// Admin Venue
Route::post('admin/venue/create', 'AdminVenueController@create');
Route::post('admin/venue/update', 'AdminVenueController@update');
// Admin Settings
Route::get('admin/welcome-message', 'AdminWelcomeMessageController@show');
Route::put('admin/welcome-message', 'AdminWelcomeMessageController@update');


// Fetch site settings for Angular - Do NOT put private things in here; no auth.
Route::get('site-settings', function() {
    return response()->json(CityHapps\SiteSetting::allMap());
});

// Static Pages
Route::get('about', function () {
	return view('main');
});

Route::group(['prefix' => 'api'], function()
{
	//Route::resource('authenticate', 'ApiController', ['only' => ['index']]);
    Route::post('authenticate', 'ApiController@authenticate');
    Route::get('authenticate/user', 'ApiController@getAuthenticatedUser');
    Route::any('{model}/{id?}', 'ApiController@handleRequest')
		->where(['model' => 'venue|category|tag|happ|agelevel']);
    Route::get('venue/{id}/similar', 'SimilarVenueController@index');
});
Route::get('api/categories', 'CategoryController@getAllCategories');
Route::get('api/favorites/{id}', 'FavoriteController@getFavorites');
Route::post('api/favorites/add', 'FavoriteController@add');
Route::post('api/favorites/check', 'FavoriteController@check');

// Limit this to only models that can be used


// Yes, this is awful. We'll change it when we get the API in a good spot
Route::any('{path?}', function()
{
	return view('main');
})->where('path', '.+');
