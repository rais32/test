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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/add_user','ApiController@addUser');
Route::put('/api/add_phone_number','ApiController@insertPhoneNumber');
Route::get('/api/get_token','ApiController@getToken');
Route::put('/api/update_barbie_score','ApiController@updateBarbieScore');
Route::put('/api/update_hotwheel_score','ApiController@updateHotwheelScore');
