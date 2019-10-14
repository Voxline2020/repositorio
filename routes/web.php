<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//SECTION Dashboard
Route::get('/','HomeController@dash')->name('dash');

Auth::routes(['reset' => false, 'verify' => false, 'logout' => false]);

//SECTION Auth
Route::get('/auth', 'Auth\LoginController@authenticate')->name('auth');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

//SECTION Contents
Route::resource('contents', 'ContentController');
Route::group(['prefix' => 'contents'], function () {
	Route::get('editTwoParam/{id}/{event_id}','ContentController@edit')->name('contents.editTwoParam');
	Route::get('{id}/view','ContentController@ScreenView')->name('contents.ScreenView');
});

//SECTION Computers
Route::resource('computers', 'ComputerController');
Route::group(['prefix' => 'computers'], function () {
	Route::get('filter_by_company_store','ComputerController@filter_by_company_store')->name('computers.filter_by_company_store');  //ruta para filtrar computadores con la compañia y sucursal a la cual pertenecen.
	Route::get('filter_by_name','ComputerController@filter_by_name')->name('computers.filter_by_name'); //ruta para filtrar computador con el nombre.
	Route::get('editTwoParam/{id}/{store_id}','ComputerController@edit')->name('computers.editTwoParam');//ruta para recoger 2 parametros(id de la sucursal,id del computador).
	Route::get('store2/','ComputerController@getStores')->name('computers.store_id'); //ruta para hacer select dinamico con compañia y sucursal.
});

//SECTION Users
Route::resource('users', 'UserController');
Route::group(['prefix' => 'users'], function () {
	//asignar role a usuario
	Route::get('{user}/roles/new','UserController@newRole')->name('users.roles.new');
	Route::put('{user}/roles/assign','UserController@assignRole')->name('users.roles.assign');
	Route::delete('{user}/roles/unassign','UserController@unassignRole')->name('users.roles.unassign');

	//asignar compañia a cliente
	Route::get('{user}/company/new','UserController@newCompany')->name('users.companies.new');
	Route::put('{user}/company/assign','UserController@assignCompany')->name('users.companies.assign');
	Route::delete('{user}/company/unassign','UserController@assignCompany')->name('users.companies.unassign');
});

//SECTION Playlists
Route::resource('playlists', 'PlaylistController');

//SECTION Events
Route::resource('events', 'EventController');
Route::group(['prefix' => 'events'], function () {
	Route::get('{id}/AssignContent', "EventController@indexAssignContent")->name('events.indexAssignContent');
	Route::get('filter/filter_by_name', "EventController@filter_by_name")->name('events.filter_by_name');
	Route::get('{eventId}/{id}', "EventController@Assign")->name('events.Assign');
});

//SECTION Companies
Route::resource('companies', 'CompanyController');
Route::group(['prefix' => 'companies'], function () {
	//events
	Route::get('/{company}/events', 'CompanyController@indexEvent')->name('companies.events.index');
	Route::get('/{company}/events/create', 'CompanyController@createEvent')->name('companies.events.create');
	Route::post('/{company}/events', 'CompanyController@storeEvent')->name('companies.events.store');
	Route::get('/{company}/events/{event}/edit', 'CompanyController@editEvent')->name('companies.events.edit');
	Route::put('/{company}/events/{event}/update', 'CompanyController@updateEvent')->name('companies.events.update');
	Route::get('/{company}/events/{event}', 'CompanyController@showEvent')->name('companies.events.show');
	Route::delete('/{company}/events/{event}', 'CompanyController@destroyEvent')->name('companies.events.destroy');

	//Stores
	Route::get('/{company}/stores', 'CompanyController@indexStore')->name('companies.stores.index');
	Route::get('/{company}/stores/create', 'CompanyController@createStore')->name('companies.stores.create');
	Route::post('/{company}/stores', 'CompanyController@storeStore')->name('companies.stores.store');
	Route::get('/{company}/stores/{store}/edit', 'CompanyController@editStore')->name('companies.stores.edit');
	Route::put('/{company}/stores/{store}/update', 'CompanyController@updateStore')->name('companies.stores.update');
	Route::get('/{company}/stores/{store}', 'CompanyController@showStore')->name('companies.stores.show');
	Route::delete('/{company}/stores/{store}', 'CompanyController@destroyStore')->name('companies.stores.destroy');
});

//SECTION Stores
Route::resource('stores', 'StoreController');
Route::group(['prefix' => 'stores'], function () {
	Route::get('{id}/show', 'StoreController@show')->name('store.show'); //ruta para obtener id
	Route::get('{id}/filter_by_name', 'StoreController@filter_by_name')->name('stores.filter_by_name'); //ruta para filtrar la sucursal con nombre

	Route::get('editTwoParam/{id}/{company_id}','StoreController@edit')->name('stores.editTwoParam'); //ruta para recoger 2 parametros(id de la sucursal,id de la compañia).
	Route::get('create/{id}','StoreController@create')->name('stores.createOneParam');// ruta para recoger 1 parametro que es la id de la compañiay crear una sucursal.



});

//SECTION Screen
Route::resource('screens', 'ScreenController');
Route::group(['prefix' => 'screens'], function () {
	Route::get('filter_by_name/{id}','ScreenController@filter_by_name')->name('screens.filter_by_name');
	Route::get('AssignContent/{id}', "ScreenController@AssignContent")->name('screens.AssignContent');
	Route::post('ScreenPlaylistAsign/{id}', "ScreenController@ScreenPlaylistAsign")->name('screens.ScreenPlaylistAsign');
	Route::get('{code}/j','ScreenJson@json')->name('screens.screen');
});

//SECTION Clients
Route::resource('clients', 'ClientController');
Route::group(['prefix' => 'clients'], function () {
	Route::get('screen/editTwoParam/{id}/{computer_id}','ScreenController@edit')->name('screens.editTwoParam'); //ruta para recoger 2 parametros(id de la sucursal,id de la compañia).
	Route::get('screen/{id}','ScreenController@create')->name('screens.createOneParam'); // ruta para recoger 1 parametro que es el id de computador y crear una pantalla.
});

//SECTION File
Route::resource('files', 'FileController');
Route::group(['prefix' => 'files'], function () {
	Route::post('file/{id}','FileController@store')->name('file.store');
});

//SECTION Generar PDF de ??
Route::get('pdf/{id}','ReportController@generate')->name('pdf.generate');

//SECTION Generar PDF de ??
Route::get('pdf/','ReportController@generateContent')->name('pdf.generateContent');

//SECTION Video Descargar
Route::get('video/{id}/d','DownloadContent@download')->name('download.content');

