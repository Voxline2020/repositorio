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
	Route::get('{content}/d','ContentController@download')->name('contents.download');
});

// //SECTION Computers
// Route::resource('computers', 'ComputerController');
// Route::group(['prefix' => 'computers'], function () {
// 	Route::get('filter/filter_computers','ComputerController@filter_computers')->name('computers.filter_computers');  //ruta para filtrar computadores con la compañia y sucursal a la cual pertenecen.
// 	// Route::get('filter/filter_by_name','ComputerController@filter_by_name')->name('computers.filter_by_name'); //ruta para filtrar computador con el nombre.
// 	Route::get('editTwoParam/{id}/{store_id}','ComputerController@edit')->name('computers.editTwoParam');//ruta para recoger 2 parametros(id de la sucursal,id del computador).
// 	Route::get('store2/','ComputerController@getStores')->name('computers.store_id'); //ruta para hacer select dinamico con compañia y sucursal.

// 	Route::get('{computer}/get/{pass}','ComputerController@getInfo')->name('computers.getInfo'); //ruta para hacer select dinamico con compañia y sucursal.
// });
Route::group(['prefix' => 'pivot'], function () {
	Route::get('{code}/get/{key}','ComputerPivotController@getInfo')->name('pivot.getInfo'); //ruta para hacer select dinamico con compañia y sucursal.
});

// SECTION Pivots
// Route::resource('pivots', 'ComputerPivotController');
// Route::group(['prefix' => 'pivots'], function () {
// 	Route::get('/', 'ComputerPivotController@index')->name('pivots.index');
// 	Route::get('/create', 'ComputerPivotController@create')->name('pivots.create');
// 	Route::post('/', 'ComputerPivotController@store')->name('pivots.store');
// 	Route::get('/{pivot}/edit', 'ComputerPivotController@edit')->name('pivots.edit');
// 	Route::put('/{pivot}', 'ComputerPivotController@update')->name('pivots.update');
// 	Route::delete('/{pivot}', 'ComputerPivotController@destroy')->name('pivots.destroy');
// 	Route::post('/{pivot}', 'ComputerPivotController@storeOnpivot')->name('pivots.storeOnpivot');
// 	Route::delete('/{pivot}/onpivot', 'ComputerPivotController@destroyOnpivot')->name('pivots.destroyOnpivot');
// 	Route::get('filter/filter_by', 'ComputerPivotController@filter_by')->name('pivots.filter_by');
// });

//SECTION Users
Route::resource('users', 'UserController');
Route::group(['prefix' => 'users'], function () {
	Route::get('/filter/filter_by','UserController@filter_by')->name('users.filter_by');
	//asignar role a usuario
	Route::get('{user}/roles/new','UserController@newRole')->name('users.roles.new');
	Route::put('{user}/roles/assign','UserController@assignRole')->name('users.roles.assign');
	Route::delete('{user}/roles/unassign','UserController@unassignRole')->name('users.roles.unassign');
	//asignar compañia a cliente
	Route::get('{user}/company/new','UserController@newCompany')->name('users.companies.new');
	Route::put('{user}/company/assign','UserController@assignCompany')->name('users.companies.assign');
	Route::delete('{user}/company/unassign','UserController@assignCompany')->name('users.companies.unassign');
});

// //SECTION Playlists
// Route::resource('playlists', 'PlaylistController');


//SECTION Events
Route::resource('events', 'EventController');
Route::group(['prefix' => 'events'], function () {
	Route::get('{id}/AssignContent', "EventController@indexAssignContent")->name('events.indexAssignContent');
	Route::get('filter/filter_by_name', "EventController@filter_by_name")->name('events.filter_by_name');
	Route::get('{eventId}/assign/{id}', "EventController@Assign")->name('events.Assign');

	//ANCHOR Asignations
	Route::get('{event}/assignations/{content}', "EventController@indexAssign")->name('events.assignations');
	Route::post('{event}/assignations/{content}', "EventController@storeAssign")->name('events.assignations.store');
	Route::get('{event}/assignations/{content}/show', "EventController@showAssign")->name('events.assignations.show');

});
//filestore
Route::post('events/fileStore', 'EventController@fileStore')->name('events.fileStore');


//SECTION Companies
Route::resource('companies', 'CompanyController');
Route::group(['prefix' => 'companies'], function () {
	Route::delete('/', 'CompanyController@destroy')->name('companies.destroy');
	Route::get('/filter/filter_by', 'CompanyController@filter_by')->name('companies.filter_by');
	//Events
	Route::group(['prefix' => '{company}/events'], function () {
		Route::get('/', 'CompanyController@indexEvent')->name('companies.events.index');
		Route::get('/create', 'CompanyController@createEvent')->name('companies.events.create');
		Route::post('/', 'CompanyController@storeEvent')->name('companies.events.store');
		Route::get('/{event}/edit', 'CompanyController@editEvent')->name('companies.events.edit');
		Route::put('/{event}', 'CompanyController@updateEvent')->name('companies.events.update');
		Route::get('/{event}', 'CompanyController@showEvent')->name('companies.events.show');
		Route::delete('/{event}', 'CompanyController@destroyEvent')->name('companies.events.destroy');
		Route::get('filter/filter_by', "CompanyController@filterEvent_by")->name('companies.events.filterEvent_by');
		//Events/Contents
		// Route::post('/fileStore', 'CompanyController@fileStore')->name('companies.fileStore');
	});
	//pivots
	Route::group(['prefix' => '{company}/pivots'], function () {
		Route::get('/', 'CompanyController@indexPivot')->name('companies.pivots.index');
		Route::get('/create', 'CompanyController@createPivot')->name('companies.pivots.create');
		Route::post('/', 'CompanyController@storePivot')->name('companies.storePivot');
		Route::get('/{pivot}', 'CompanyController@showPivot')->name('companies.pivots.show');
		Route::get('/{pivot}/edit', 'CompanyController@editPivot')->name('companies.pivots.edit');
		Route::put('/{pivot}', 'CompanyController@updatePivot')->name('companies.pivots.update');
		Route::delete('/{pivot}', 'CompanyController@destroyPivot')->name('companies.pivots.destroy');
		Route::post('/{pivot}', 'CompanyController@storeOnpivot')->name('companies.storeOnpivot');
		Route::delete('/{pivot}/onpivot', 'CompanyController@destroyOnpivot')->name('companies.destroyOnpivot');
		Route::get('/filter/filter_by', 'CompanyController@filterPivot_by')->name('companies.pivots.filter_by');
	});
	//Computers
	Route::group(['prefix' => '{company}/computers'], function () {
		Route::get('/', 'CompanyController@indexComputer')->name('companies.computers.index');
		Route::get('/create', 'CompanyController@createComputer')->name('companies.computers.create');
		Route::post('/', 'CompanyController@storeComputer')->name('companies.storeComputer');
		Route::get('/{computer}', 'CompanyController@showComputer')->name('companies.computers.show');
		Route::get('/{computer}/edit','CompanyController@editComputer')->name('companies.computers.edit');
		Route::put('/{computer}','CompanyController@updateComputer')->name('companies.computers.update');
		Route::delete('/{computer}', 'CompanyController@destroyComputer')->name('companies.computers.destroy');
		Route::get('filter/filter_computers', 'CompanyController@filter_computers')->name('companies.computers.filter_computers');
		//Computers/screens
		Route::post('/{computer}/screens', 'CompanyController@storeScreen')->name('companies.storeScreen');
		Route::get('/{computer}/screens/{screen}','CompanyController@showScreen')->name('companies.computers.showScreen');
		Route::get('/{computer}/screens/{screen}/edit','CompanyController@editScreen')->name('companies.computers.editScreen');
		Route::put('/{computer}/screens', 'CompanyController@updateScreen')->name('companies.computers.updateScreen');
		Route::delete('/{computer}/screens/{screen}', 'CompanyController@destroyScreen')->name('companies.computers.destroyScreen');
		Route::put('/{computer}/screens/{screen}/status','CompanyController@changeStatusScreen')->name('companies.computers.changeStatusScreen');
		Route::post('/{computer}/screens/{screen}/assign','CompanyController@eventAssignScreen')->name('companies.computers.eventAssignScreen');
		Route::put('/{computer}/screens/{screen}/clone','CompanyController@cloneEventScreen')->name('companies.computers.cloneEventScreen');
		Route::put('/{computer}/screens/{screen}/change','CompanyController@changeOrderScreen')->name('companies.computers.changeOrderScreen');

	});

	//Stores
	Route::group(['prefix' => '{company}/stores'], function () {
		//Indice de tiendas
		Route::get('/', 'CompanyController@indexStore')->name('companies.stores.index');
		Route::get('/create', 'CompanyController@createStore')->name('companies.stores.create');
		Route::post('/', 'CompanyController@storeStore')->name('companies.stores.store');
		Route::get('/{store}/edit', 'CompanyController@editStore')->name('companies.stores.edit');
		Route::put('/{store}', 'CompanyController@updateStore')->name('companies.stores.update');
		Route::get('/{store}', 'CompanyController@showStore')->name('companies.stores.show');
		Route::delete('/{store}', 'CompanyController@destroyStore')->name('companies.stores.destroy');
		Route::get('/filter/filterStore', 'CompanyController@filterStore')->name('companies.filterStore');

		Route::group(['prefix' => '{store}/computers'], function () {
			// Route::get('/', 'CompanyController@indexStoreComputer')->name('companies.stores.computers.index');
			// Route::get('/create', 'CompanyController@createComputerStore')->name('companies.stores.computers.create');
			// Route::post('/', 'CompanyController@storeStoreComputer')->name('companies.stores.computers.store');
			// Route::get('/{computer}/edit', 'CompanyController@editStoreComputer')->name('companies.stores.computers.edit');
			// Route::put('/{computer}', 'CompanyController@updateStoreComputer')->name('companies.stores.computers.update');
			// Route::get('/{computer}', 'CompanyController@showStoreComputer')->name('companies.stores.computers.show');
			// Route::delete('/{computer}', 'CompanyController@destroyStoreComputer')->name('companies.stores.computers.destroy');

			// Route::group(['prefix' => '{computer}/screens'], function () {
			// 	Route::get('/', 'CompanyController@indexStoreComputersScreen')->name('companies.stores.computers.screens.index');
			// 	Route::get('/create', 'CompanyController@createComputerStoreScreen')->name('companies.stores.computers.screens.create');
			// 	Route::post('/', 'CompanyController@storeStoreComputerScreen')->name('companies.stores.computers.screens.store');
			// 	Route::get('/{screen}/edit', 'CompanyController@editStoreComputerScreen')->name('companies.stores.computers.screens.edit');
			// 	Route::put('/{screen}', 'CompanyController@updateStoreComputerScreen')->name('companies.stores.computers.screens.update');
				// Route::get('/{screen}', 'CompanyController@showStoreComputerScreen')->name('companies.stores.computers.screens.show');
			// 	Route::delete('/{screen}', 'CompanyController@destroyStoreComputerScreen')->name('companies.stores.computers.screens.destroy');
			// });
		});


	});



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
	Route::get('/create','ScreenController@create')->name('screens.createOneParam');
	Route::get('AssignContent/{id}', "ScreenController@AssignContent")->name('screens.AssignContent');
	Route::post('ScreenPlaylistAsign/{id}', "ScreenController@ScreenPlaylistAsign")->name('screens.ScreenPlaylistAsign');
	Route::get('{code}/j','ScreenJson@json')->name('screens.screen');
});

//SECTION Clients
Route::get('clients','ClientController@index')->name('clients.index'); //ruta para recoger 2 parametros(id de la sucursal,id de la compañia)
// Route::resource('clients', 'ClientController');
Route::group(['prefix' => 'clients'], function () {
	Route::put('screen/status/{id}','ScreenController@changeStatus')->name('screens.changeStatus');//envia el id de la pantalla junto con el estado (0 o 1) para realizar el cambio
	Route::get('screen/editTwoParam/{id}/{computer_id}','ScreenController@edit')->name('screens.editTwoParam'); //ruta para recoger 2 parametros(id de la sucursal,id de la compañia)
	Route::get('screen/{id}','ClientController@show')->name('clients.show'); // ruta para mostrar contenido de la pantalla
	// Route::put('screen/changeUp/{id}','ClientController@changeUp')->name('clients.changeUp');
	// Route::put('screen/changeDown/{id}','ClientController@changeDown')->name('clients.changeDown');
	// Route::put('screen/changeJump','ClientController@changeJump')->name('clients.changeJump');
	// Route::put('screen/clone','ClientController@clone')->name('clients.clone');
	Route::put('screen/assign/{id}','ScreenController@eventAssign')->name('screens.eventAssign');
	Route::put('screen/clone','ScreenController@cloneEvent')->name('screens.cloneEvent');
	Route::put('screen/change','ScreenController@changeOrder')->name('screens.changeOrder');
	// Route::get('screen/{id}/show', 'ScreenController@show')->name('screens.show');
	Route::get('filter_by_name','ClientController@filter_by_name')->name('clients.filter_by_name');
	Route::get('filter_screen','ClientController@filter_screen')->name('clients.filter_screen');
	Route::get('/events', "EventController@index")->name('clients.events.index');
	Route::get('/events/{event}/', "EventController@showClient")->name('clients.events.show');

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

