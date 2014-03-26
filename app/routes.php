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

/**
 * Login routes
 */
Route::get('/', 'UserController@home');
Route::get('login', 'UserController@login');
Route::get('logout', 'UserController@logout');
Route::post('login/process', array('before' => 'csrf', 'uses' => 'UserController@loginProcess'));

/**
 * Signup routes
 */
Route::get('signup', 'UserController@signup');
Route::post('signup/process', array('before' => 'csrf', 'uses' => 'UserController@signupProcess'));
Route::get('confirm', array('before' => 'auth', 'uses' => 'UserController@confirm'));
Route::post('confirm/process', array('before' => 'csrf', 'uses' => 'UserController@signupConfirm'));
Route::get('signup/link/{id}/{code}', 'UserController@signupLink')->where('id', '[0-9]+')->where('code', '[0-9a-z]+');

/**
 * Profile routes
 */
Route::get('profile', 'UserController@profile');
Route::post('profile/search', array('before' => 'csrf', 'uses' => 'UserController@profileSearch'));
Route::get('profile/change', 'UserController@change');
Route::post('profile/change/process', 'UserController@profileChangeProcess');