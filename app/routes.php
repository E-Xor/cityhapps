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

App::missing(function($exception) {

	return View::make('home');

});


Route::get('events', 'EventfulController@events');



	Route::get('auth/status', 'SessionsController@index');

	Route::post('auth/login', 'SessionsController@login');

	Route::get('auth/logout', 'SessionsController@logout');

	Route::resource('user', 'UserController');

	Route::resource('category', 'CategoryController');

	Route::post('auth/login-fb', 'SessionsController@fbNewLogin');



// Route::get("")