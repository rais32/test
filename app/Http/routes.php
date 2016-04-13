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
Route::get('/test','ApiController@coba');
Route::get('/api/get_token','ApiController@getToken');
Route::post('/api/add_user','ApiController@addUser');
Route::post('/api/get_leader_board_hotwheel','ApiController@getLeaderBoardHotwheel');
Route::post('/api/get_leader_board_barbie','ApiController@getLeaderBoardBarbie');
Route::post('/api/get_coupon','ApiController@getCouponWinner');
Route::post('/api/add_phone_number','ApiController@insertPhoneNumber');
Route::post('/api/update_barbie_score','ApiController@updateBarbieScore');
Route::post('/api/update_hotwheel_score','ApiController@updateHotwheelScore');
