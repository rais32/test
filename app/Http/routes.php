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

Route::get('/', 'homeController@index');
//Route::get('/test','ApiController@coba');
Route::controllers([
	'auth' => 'Auth\AuthController',
   	'password' => 'Auth\PasswordController',
]);
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::group(['middleware' => 'auth'],function(){
	
	Route::get('add_coupon','homeController@showAddCoupon');
	Route::get('list_coupons','homeController@showListCoupon');
	Route::get('list_winners','homeController@showWinner');
	Route::get('list_users','homeController@showUsers');
	Route::get('options','homeController@showOptions');
	Route::get('send_to_user/{id}','homeController@showSendFormNotif');
	Route::get('send_to_all','homeController@showSendFormAll');
	Route::post('update_total_winners','homeController@updateTotalWinners');
	Route::post('update_probability','homeController@updateProbability');
	Route::post('post_send_all','homeController@postSendAll');
	Route::post('post_send_user','homeController@postSendUser');
	Route::post('upload_coupon', 'homeController@uploadCoupon');	
});

Route::get('/api/get_token','ApiController@getToken');
Route::post('/api/add_user','ApiController@addUser');
Route::post('/api/get_leader_board_hotwheel','ApiController@getLeaderBoardHotwheel');
Route::post('/api/get_leader_board_barbie','ApiController@getLeaderBoardBarbie');
Route::post('/api/get_coupon','ApiController@getCouponWinner');
Route::post('/api/add_phone_number','ApiController@insertPhoneNumber');
Route::post('/api/update_barbie_score','ApiController@updateBarbieScore');
Route::post('/api/update_hotwheel_score','ApiController@updateHotwheelScore');
