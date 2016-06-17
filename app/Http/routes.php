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

Route::resource('city', 'CityController');
Route::resource('user', 'UserController');
Route::post('user/{user_id}/cities', 'UserController@saveCities');
Route::post('user/{user_id}/default/city', 'UserController@setDefaultCity');

Route::get('auth/login', 'AuthController@getlogin');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);


