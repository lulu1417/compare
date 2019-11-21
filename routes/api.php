<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('vs/login','CompareController@login');
Route::get('vs/login','CompareController@room');
Route::post('vs/play','CompareController@send');
Route::get('vs/play','CompareController@game');
Route::get('vs/history','CompareController@record');
Route::delete('vs/leave','CompareController@leave');

Route::post('number','CodeController@play');
Route::get('number','CodeController@start');

Route::get('bulls','BullsController@start');
Route::post('bulls','BullsController@play');
