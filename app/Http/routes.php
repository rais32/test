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
Route::post('post_add_user','HomeController@AjaxAddUser');
//Route::get('test', 'homeController@testDulu');
//Route::get('/test','ApiController@coba');

