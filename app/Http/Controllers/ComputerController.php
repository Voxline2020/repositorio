<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateComputerRequest;
use App\Http\Requests\UpdateComputerRequest;
use App\Repositories\ComputerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Store;
use App\Models\Computer;
use App\Models\AccessType;
use Flash;
use Response;

class ComputerController extends AppBaseController
{
	/** @var  StoreRepository */
	private $computerRepository;

	public function __construct(ComputerRepository $computerRepo)
	{
		$this->computerRepository = $computerRepo;
	}
	//mostrar computadores
	public function index(Request $request)
	{
		$types= AccessType::all();
		$lists = Company::all();
		$stores = Store::all();
		$companies = Company::all();
		$computer = $this->computerRepository->all();
		return view('computers.index', compact('companies', 'stores', 'lists','types'))
			->with('computers', $computer);
	}
	//mostrar computadores con id en especifico
	public function show($id)
	{
		$computer = Computer::where('store_id', $id)->paginate();
		return view(
			'computer.index',
			['computers' => $computer]
		);
	}
	//Creacion computadores
	//Vista de creacion
	public function create()
	{
		$types = AccessType::all();
		$stores = Store::all();
		return view('computer.create', compact('stores', 'types'));
	}

	//Request de creacion (POST)
	public function store(CreateComputerRequest $request)
	{
		$input = $request->all();
		$computer = $this->computerRepository->create($input);
		Flash::success('Computador agregado con exito.');
		return redirect(route('computers.index'));
	}
	//Editar computadores
	//vista de editar
	public function edit($id, $store_id)
	{
		$types = AccessType::all();
		$stores = Store::where('id', $store_id)->get();
		$computer = $this->computerRepository->find($id);
		if (empty($computer)) {
			Flash::error('Computador no encontrado');
			return redirect(route('computers.index'));
		}
		return view('computer.edit', compact('stores', 'types'))->with('computer', $computer);
	}
	//Request de editar (POST)
	public function update($id, UpdateComputerRequest $request)
	{
		$computer = $this->computerRepository->find($id);
		if (empty($computer)) {
			Flash::error('Computador no encontrado');
			return redirect(route('computers.index'));
		}
		$computer = $this->computerRepository->update($request->all(), $id);
		Flash::success('Computador editado');
		return redirect(route('computers.index'));
	}
	//Eliminar computadores
	public function destroy($id)
	{
		$computer = $this->computerRepository->find($id);
		if (empty($computer)) {
			Flash::error('Computador no encontrado');
			return redirect(route('computers.index'));
		}
		$this->computerRepository->delete($id);
		Flash::success('Computador borrado');
		return redirect(route('computers.index'));
	}
	// filtros y otros.
	//llenado de select dinamico
	public function getStores(Request $request)
	{
		if ($request->ajax()) {
			$stores = Store::stores($request->id);
			return response()->json($stores);
		}
	}
	//filtrar computadores dependiendo del nombre
	public function filter_by_name(Request $request)
	{
		$types= AccessType::all();
		$lists = Company::all();
		$stores = Store::all();
		$companies = Company::all();
		$filter= $request->get('codeFiltrar');
		$computer = Computer::where('code','LIKE',"%$filter%")->paginate();
		return view('computers.index', compact('companies', 'stores', 'lists','types'))->with('computers', $computer);
	}
	//filtrar computadores dependiendo de la compañia y sucursal.
	public function filter_by_company_store(Request $request)
	{
		$types= AccessType::all();
		$lists = Company::all();
		$stores = Store::all();
		$companies = Company::all();
		$computer = Computer::where('store_id', $request->get('store'))->orWhere('type_id', $request->get('type'))->paginate();
		return view('computers.index', compact('companies', 'stores', 'lists', 'types'))->with('computers', $computer);
	}
}
