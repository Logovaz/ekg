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
Route::get('confirm', 'UserController@confirm');
Route::post('signup/process', array('before' => 'csrf', 'uses' => 'UserController@signupProcess'));
Route::post('confirm/process', array('before' => 'csrf', 'uses' => 'UserController@confirmProcess'));
Route::get('signup/link/{id}/{code}', 'UserController@confirmLink')->where('id', '[0-9]+')->where('code', '[0-9a-z]+');

/**
 * Information routes
 */

Route::get('information', array('before' => 'auth', 'uses' => 'UserController@information'));
Route::post('information/process', array('before' => 'auth', 'uses' => 'UserController@informationProcess'));

/**
 * Profile routes
 */

Route::get('profile', array('before' => 'auth', 'uses' => 'UserController@profile'));
Route::get('control', array('before' => 'auth', 'uses' => 'UserController@control'));

/**
 * User routes
 */
Route::get('user/search', array('before' => array('auth', 'admin'), 'uses' => 'UserController@userSearch'));
Route::post('user/search/process', 'UserController@userSearchProcess');
Route::get('user/{id}', 'UserController@userView')->where('id', '[0-9]+');
Route::get('user/change/{id}', 'UserController@change')->where('id', '[0-9]+');
Route::post('user/change/process', 'UserController@userChangeProcess');
Route::get('article/{id}', 'UserController@article')->where('id', '[0-9]+');

/**
 * Ajax routes
 */

Route::post('ajax/getPlotExample', 'EcgController@getExampleData');
Route::post('ajax/getPlot', 'EcgController@getPlot');
Route::post('ajax/sendMessage', 'UserController@sendAjaxMessage');

/**
 * Common routes
 */

Route::get('ecg', 'EcgController@example');
Route::get('graph/{user_id}/{ecg_id}', 'EcgController@graph')->where('user_id', '[0-9]+')->where('ecg_id', '[0-9_]+');

Route::get('messages', 'UserController@messages');
Route::post('messages/send', array('before' => 'auth', 'uses' => 'UserController@sendMessage'));

Route::get('patients', array('before' => array('auth', 'doctor'), 'uses' => 'UserController@patients'));
Route::post('patients/search', array('before' => array('auth', 'doctor'), 'uses' => 'UserController@patientsSearch'));
Route::post('patients/add', array('before' => array('auth', 'doctor'), 'uses' => 'UserController@patientAdd'));