<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Company;
use App\Models\Store;

class CompanyController extends AppBaseController
{
	/** @var  CompanyRepository */
	private $companyRepository;

	public function __construct(CompanyRepository $companyRepo)
	{
		$this->companyRepository = $companyRepo;
	}
	//mostrar compañias
	public function index(Request $request)
	{
		$companies = $this->companyRepository->all();
		return view('companies.index')
			->with('companies', $companies);
	}
	//mostrar compañias con id en especifico
	public function show($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.show')->with('company', $company);
	}
	//creacion compañias
	//Vista de creacion
	public function create()
	{
		return view('companies.create');
	}
	//Request de creacion (POST)
	public function store(CreateCompanyRequest $request)
	{
		$input = $request->all();
		$company = $this->companyRepository->create($input);
		Flash::success('Compañia agregada con exito.');
		return redirect(route('companies.index'));
	}
	//editar compañias
	//Vista de editar
	public function edit($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.edit')->with('company', $company);
	}
	//Request de editar (POST)
	public function update($id, UpdateCompanyRequest $request)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('compañia no encontrada');
			return redirect(route('companies.index'));
		}
		$company = $this->companyRepository->update($request->all(), $id);
		Flash::success('compañia editada');
		return redirect(route('companies.index'));
	}
	//eliminar compañias
	public function destroy($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('compañia no encontrada');
			return redirect(route('companies.index'));
		}
		$this->companyRepository->delete($id);
		Flash::success('Compañia borrada');
		return redirect(route('companies.index'));
	}

	public function dash(){
		$companies = $this->companyRepository->all();
		return view('companies.index')
			->with('companies', $companies);
	}

	//SECTION Eventos Compañia
	public function indexEvent(Request $request)
	{
		$companies = $this->companyRepository->all();
		return view('companies.index')
			->with('companies', $companies);
	}
	public function showEvent($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.show')->with('company', $company);
	}
	public function createEvent()
	{
		return view('companies.create');
	}
	public function storeEvent(CreateCompanyRequest $request)
	{
		$input = $request->all();
		$company = $this->companyRepository->create($input);
		Flash::success('Compañia agregada con exito.');
		return redirect(route('companies.index'));
	}
	public function editEvent($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.edit')->with('company', $company);
	}
	public function updateEvent($id, UpdateCompanyRequest $request)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('compañia no encontrada');
			return redirect(route('companies.index'));
		}
		$company = $this->companyRepository->update($request->all(), $id);
		Flash::success('compañia editada');
		return redirect(route('companies.index'));
	}
	public function destroyEvent($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('compañia no encontrada');
			return redirect(route('companies.index'));
		}
		$this->companyRepository->delete($id);
		Flash::success('Compañia borrada');
		return redirect(route('companies.index'));
	}

	//SECTION Sucursales Compañia
	public function indexStore(Company $company, Request $request)
	{
		$stores = Store::where('company_id', $company->id);
		if(isset($request['nameFilter']) && !empty($request['nameFilter'])){
			$stores = $stores->where('name', 'like', '%'.$request['nameFilter'].'%');
		}
		else if(isset($request['addressFilter']) && !empty($request['addressFilter'])){
			$stores = $stores->where('address', 'like', '%'.$request['addressFilter'].'%');
		}
		$stores = $stores->get();
		return view('companies.stores.index',compact('company', 'stores'));
	}
	public function showStore(Company $company, Store $store)
	{
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.stores.index', compact('company')));
		}
		return view('companies.stores.show', compact('company','store'));
	}
	public function createStore(Company $company)
	{
		return view('companies.stores.create', compact('company'));
	}

	public function storeStore(Company $company, Request $request)
	{
		$input = $request->all();

		$store = Store::create($input);
		Flash::success('Tienda agregada con exito.');
		return redirect(route('companies.stores.index',compact('company')));
	}
	public function editStore(Company $company, Store $store)
	{
		if (empty($store) || empty($company)) {
			Flash::error('Tienda no encontrada');
			return redirect(route('stores.index'));
		}
		return view('companies.stores.edit', compact('company', 'store'));
	}
	public function updateStore(Company $company, Store $store, Request $request)
	{
		if (empty($store)) {
			Flash::error('Tienda no encontrada');
			return redirect(url()->previous());
		}
		$store->fill($request->all());
		$store->save();
		Flash::success('Tienda editada');
		return redirect(route('companies.stores.index',$company));
	}
	public function destroyStore(Company $company, Store $store)
	{
		if (empty($store)) {
			Flash::error('tienda no encontrada');
			return redirect(route('companies.stores.index',$company));
		}
		$store->delete();
		Flash::success('Tienda borrada');
		return redirect(route('companies.stores.index',$company));
	}

	//SECTION Computadores Sucursales Compañia
	public function indexStoreComputers(Company $company, Request $request)
	{
		$stores = Store::where('company_id', $company->id);
		if(isset($request['nameFilter']) && !empty($request['nameFilter'])){
			$stores = $stores->where('name', 'like', '%'.$request['nameFilter'].'%');
		}
		else if(isset($request['addressFilter']) && !empty($request['addressFilter'])){
			$stores = $stores->where('address', 'like', '%'.$request['addressFilter'].'%');
		}
		$stores = $stores->get();
		return view('companies.stores.index',compact('company', 'stores'));
	}

	public function showStoreComputers($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.show')->with('company', $company);
	}
	public function createStoreComputers(Company $company)
	{
		return view('companies.stores.create', compact('company'));
	}

	public function storeStoreComputers(Company $company, Request $request)
	{
		$input = $request->all();

		$store = Store::create($input);
		Flash::success('Tienda agregada con exito.');
		return redirect(route('companies.stores.index',compact('company')));
	}
	public function editStoreComputers(Company $company, Store $store)
	{
		if (empty($store) || empty($company)) {
			Flash::error('Tienda no encontrada');
			return redirect(route('stores.index'));
		}
		return view('companies.stores.edit', compact('company', 'store'));
	}
	public function updateStoreComputers(Company $company, Store $store, Request $request)
	{
		if (empty($store)) {
			Flash::error('Tienda no encontrada');
			return redirect(url()->previous());
		}
		$store->fill($request->all());
		$store->save();
		Flash::success('Tienda editada');
		return redirect(route('companies.stores.index',$company));
	}
	public function destroyStoreComputers(Company $company, Store $store)
	{
		if (empty($store)) {
			Flash::error('tienda no encontrada');
			return redirect(route('companies.stores.index',$company));
		}
		$store->delete();
		Flash::success('Tienda borrada');
		return redirect(route('companies.stores.index',$company));
	}

	//SECTION Pantallas Computadores Sucursales Compañia
	public function indexStoreComputersScreens(Company $company, Request $request)
	{
		$stores = Store::where('company_id', $company->id);
		if(isset($request['nameFilter']) && !empty($request['nameFilter'])){
			$stores = $stores->where('name', 'like', '%'.$request['nameFilter'].'%');
		}
		else if(isset($request['addressFilter']) && !empty($request['addressFilter'])){
			$stores = $stores->where('address', 'like', '%'.$request['addressFilter'].'%');
		}
		$stores = $stores->get();
		return view('companies.stores.index',compact('company', 'stores'));
	}
	public function showStoreComputersScreens($id)
	{
		$company = $this->companyRepository->find($id);
		if (empty($company)) {
			Flash::error('Compañia no encontrada');
			return redirect(route('companies.index'));
		}
		return view('companies.show')->with('company', $company);
	}
	public function createStoreComputersScreens(Company $company)
	{
		return view('companies.stores.create', compact('company'));
	}

	public function storeStoreComputersScreens(Company $company, Request $request)
	{
		$input = $request->all();

		$store = Store::create($input);
		Flash::success('Tienda agregada con exito.');
		return redirect(route('companies.stores.index',compact('company')));
	}
	public function editStoreComputersScreens(Company $company, Store $store)
	{
		if (empty($store) || empty($company)) {
			Flash::error('Tienda no encontrada');
			return redirect(route('stores.index'));
		}
		return view('companies.stores.edit', compact('company', 'store'));
	}
	public function updateStoreComputersScreens(Company $company, Store $store, Request $request)
	{
		if (empty($store)) {
			Flash::error('Tienda no encontrada');
			return redirect(url()->previous());
		}
		$store->fill($request->all());
		$store->save();
		Flash::success('Tienda editada');
		return redirect(route('companies.stores.index',$company));
	}
	public function destroyStoreComputersScreens(Company $company, Store $store)
	{
		if (empty($store)) {
			Flash::error('tienda no encontrada');
			return redirect(route('companies.stores.index',$company));
		}
		$store->delete();
		Flash::success('Tienda borrada');
		return redirect(route('companies.stores.index',$company));
	}

}
