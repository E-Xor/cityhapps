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

Route::get('login', 'SessionsController@create');

Route::get('logout', 'SessionsController@destroy');

Route::resource('sessions', 'SessionsController');

Route::get('admin', function() {

	return Auth::user();

})->before('auth');


Route::get('/', 'HomeController@showWelcome');

Route::get('events', 'EventfulController@events');

// User::create([
// 	'username' => 'Craigers', 
// 	'email' => 'ckahle33@gmail.com', 
// 	"password" => Hash::make('password')


// ]);

// Route::get("")