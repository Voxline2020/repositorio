<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Repositories\StoreRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Store;
use Flash;
use Response;

class StoreController extends AppBaseController
{
	/** @var  StoreRepository */
	private $storeRepository;


	public function __construct(StoreRepository $storeRepo)
	{
		$this->middleware('admin');
		$this->storeRepository = $storeRepo;
	}
	//Mostrar sucursales
	public function index(Request $request)
	{
		$id = auth()->user()->company_id;
    	$company = Company::where('id', $id)->first();
		$stores = $this->storeRepository->all();
		return view('store.index')
			->with('stores', $stores)->with('company', $company);
	}
	//Mostrar sucursales con id en especifico
	public function show($id)
	{
		$companies = Company::where('id', $id)->first();
		$store = Store::where('company_id', $id)->paginate();
		return view(
			'store.index',
			['stores' => $store],
			compact('companies')
		);
	}
	//creacion de sucursales
	//vista de creacion
	public function create($id)
	{
		$companies = Company::where('id', $id)->get();
		return view('store.create', compact('companies'));
	}
	//Request de creacion (POST)
	public function store(CreateStoreRequest $request)
	{
		$companies = Company::where('id', $request->get('company_id'))->first();
		$input = $request->all();
		$store = $this->storeRepository->create($input);
		Flash::success('Tienda agregada con exito.');
		return redirect(route('companies.index'));
	}
	//editar sucursales
	//vista de editar
	public function edit($id,  $company_id)
	{
		$companies = Company::where('id', $company_id)->get();
		$store = $this->storeRepository->find($id);
		if (empty($store)) {
			Flash::error('Tienda no encontrada');
			return redirect(route('stores.index'));
		}
		return view('store.edit', compact('companies'))->with('store', $store);
	}
	//Request de editar (POST)
	public function update($id, UpdateStoreRequest $request)
	{
		$store = $this->storeRepository->find($id);
		if (empty($store)) {
			Flash::error('Tienda no encontrada');
			return redirect(url()->previous());
		}
		$store = $this->storeRepository->update($request->all(), $id);
		Flash::success('Tienda editada');
		return redirect(route('companies.index'));
	}
	//eliminar sucursales
	public function destroy($id)
	{
		$store = $this->storeRepository->find($id);
		if (empty($store)) {
			Flash::error('tienda no encontrada');
			return redirect(url()->previous());
		}
		$this->storeRepository->delete($id);
		Flash::success('Tienda borrada');
		return redirect(url()->previous());
	}
	//filtros
	//filtrar sucursal por nombre.
	public function filter_by_name(Request $request, $id)
	{
		$companies = Company::where('id', $id)->first();
		$filter = $request->get('nameFiltrar');
		$store = Store::where('name','LIKE',"%$filter%")->paginate();
		return view('store.index', compact('companies'))->with('stores', $store);
	}
}
