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


// Route::middleware('api')->get('screens', 'ScreenController@getScreens');

Route::middleware('api')->get('screens', 'ScreenController@apiIndex')->name('api.screens.index');
Route::middleware('api')->get('screens/{screen}', 'ScreenController@apiView')->name('api.screen.view');
Route::middleware('api')->get('screens/{screen}', 'ScreenController@apiExist')->name('api.screen.exist');

Route::middleware('api')->get('computers', 'ComputerController@apiIndex')->name('api.computer.index');
Route::middleware('api')->get('computers/{computer}', 'ComputerController@apiView')->name('api.computer.view');
Route::middleware('api')->get('computers/{computer}/exist', 'ComputerController@apiExist')->name('api.computer.exist');
Route::middleware('api')->put('computers/{computer}/put','ComputerController@apiPut')->name('api.computer.put');
Route::middleware('api')->post('computers/{computer}','ComputerController@apiPost')->name('api.computer.post');
Route::middleware('api')->delete('computers/{computer}/delete','ComputerController@apiPost')->name('api.computer.post');

Route::middleware('api')->get('devices', 'ScreenController@apiIndex')->name('api.computer.index');
Route::middleware('api')->get('devices/{code}', 'ScreenController@apiView')->name('api.device.view');
Route::middleware('api')->get('devices/{code}/exist', 'ScreenController@apiExist')->name('api.device.exist');
Route::middleware('api')->put('devices/{code}/put','ScreenController@apiPut')->name('api.device.put');
Route::middleware('api')->post('devices/{code}','ScreenController@apiPost')->name('api.device.post');
Route::middleware('api')->delete('devices/{code}/','ScreenController@apiPost')->name('api.device.post');

//Gustavo
Route::middleware('api')->post('upScreenShotCms', 'ClientStoreController@upScreenShotApi');






