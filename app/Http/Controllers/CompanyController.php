<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Str as Str;
use Illuminate\Http\Request;
use App\Models\EventAssignation;
use App\Models\ComputerOnPivot;
use App\Models\ComputerPivot;
use App\Models\AccessType;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Company;
use App\Models\screen;
use App\Models\Store;
use App\Models\Event;
use Carbon\Carbon;
use Flash;
use Auth;


class CompanyController extends AppBaseController
{
  /** @var  CompanyRepository */
  private $companyRepository;

  public function __construct(CompanyRepository $companyRepo)
  {
		$this->middleware('admin')->except(['createEvent','storeEvent','editEvent','destroyEvent','updateEvent','showEvent','indexEvent']);
    $this->companyRepository = $companyRepo;
  }
  //mostrar compañias
  public function index(Request $request)
  {
		$computers = Computer::all();
		$pivots = ComputerPivot::all();
    $companies = Company::paginate();
    return view('companies.index')
      ->with('companies', $companies)->with('computers', $computers)->with('pivots', $pivots);
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
		dd($request);
  }
  //eliminar compañias
  public function destroy(Request $request)
  {
    $company = $this->companyRepository->find($request->company);
    if (empty($company)) {
      Flash::error('compañia no encontrada.');
      return redirect(route('companies.index'));
    }
    $this->companyRepository->delete($request->company);
    Flash::success('Compañia borrada.');
    return redirect(route('companies.index'));
  }
  public function dash()
  {
    $companies = $this->companyRepository->all();
    return view('companies.index')
      ->with('companies', $companies);
	}
	public function filter_by(Request $request)
	{
		// dd($request);
		$computers = Computer::all();
		$pivots = ComputerPivot::all();
    $companies = Company::all();
		if($request->nameFiltrar==null){
			Flash::error('Debes ingresar almenos un filtro para la busqueda.');
			return redirect(route('companies.index'));
		}
		if($request->nameFiltrar!=null){
			$companies = Company::where('name','like',"%$request->nameFiltrar%")->paginate();
		}
		if($companies->count()==0){
			Flash::info('No se encontro ningun resultado.');
			return redirect(route('companies.index'));
		}
		return view('companies.index')->with('companies', $companies)->with('computers', $computers)->with('pivots', $pivots);

	}
	//companies//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //ANCHOR Eventos Compañia
  public function indexEvent(Company $company, Request $request)
  {
    $events = Event::with('contents')->where('company_id', $company->id)->paginate();
    $lists = Event::with('contents')->where('company_id', $company->id)->paginate();
    $listsStore = Store::all();
    return view('companies.events.index', compact('lists', 'listsStore', 'events', 'company'));
  }
  public function showEvent(Company $company,Event $event)
  {
		//comprobamos que el evento tenga contenido
		if($event->contents->count()!=0){
			//obtenemos los contenidos del evento
			$contentsList = [];
			foreach ($event->contents AS $content) {
				array_push($contentsList, $content->id);
			};
			//traemos las asignaciones de eventos que coincidan con los contenidos del evento que estamos revisando
			$eventAssigns = EventAssignation::where('content_id',$contentsList)->get();
			//extraemos las pantallas de los contenidos asignados
			$list = [];
			foreach ($eventAssigns AS $asign) {
				array_push($list, $asign->screen_id);
			};
			//extraemos las pantallas
			$screens= screen::find($list);
			return view('companies.events.show',['company' => $company,'event'=>$event], compact('company','event'))->with('screens',$screens);
		}else{
			$screens= $event->contents;
			return view('companies.events.show',['company' => $company,'event'=>$event], compact('company','event'))->with('screens',$screens);
		}
  }
  public function createEvent(Company $company)
  {
    return view('companies.events.create', compact('company'));
  }
  public function storeEvent(Company $company, Request $request)
  {
		$name = Event::where('company_id',$request->company_id)->where('name',$request->name)->get();
		if($name->count() != 0){
			Flash::error('El evento "'.$request->name.'" ya existe.');
			return redirect(route('companies.events.create', $company));
		}
		//Format Init Date
		$request->merge([
		"initdate"=> Carbon::createFromFormat('d/m/Y H:i',$request["initdate"])->toDateTimeString(),
		"enddate"=> Carbon::createFromFormat('d/m/Y H:i',$request["enddate"])->toDateTimeString(),
		'state'=>'0',
		'slug'=>Str::slug($request['name'])
		]);
		if($request->initdate > $request->enddate){
			Flash::error('La fecha de termino no puede ser inferior a la fecha de inicio.');
			return redirect(route('companies.events.create', $company));
		}else{
			$input = $request->all();
			Event::create($input);
			$id = [];
			$callevent =  Event::where('company_id',$request->company_id)->where('name',$request->name)->get();
			foreach($callevent as $e){
				array_push($id,$e->id);
			}
			$event = Event::find($id);
			Flash::success('Evento agregado exitosamente');
			if (Auth::user()->hasRole('Administrador')){
				return redirect(route('companies.events.index', $company));
			}else{
				return redirect(route('events.show', [ $event[0]->id]));
			}
		}
    Flash::error('Error al agregar el evento.');
		if (Auth::user()->hasRole('Administrador')){
			return redirect(route('companies.events.index', $company));
		}else{
			return redirect(route('events.show', [ $event[0]->id]));
		}
  }
  public function editEvent(Company $company,Event $event )
  {
    return view('companies.events.edit')->with('company', $company)->with('event', $event);
  }
  public function updateEvent(Company $company,Event $event, UpdateCompanyRequest $request)
  {
		$request->merge([
				"initdate"=> Carbon::createFromFormat('d/m/Y H:i',$request["initdate"])->toDateTimeString(),
				"enddate"=> Carbon::createFromFormat('d/m/Y H:i',$request["enddate"])->toDateTimeString(),
				]);
    $event->update($request->all());
    Flash::success('Evento editado exitosamente.');
		// return redirect(route('events.show',['id' => $id]));
		return redirect()->route('companies.events.edit', ['company'=>$company,'event'=>$event]);
  }
  public function destroyEvent(Company $company,Event $event)
  {
    if (empty($event->id)) {
      Flash::error('Evento no encontrado.');
      return redirect(route('companies.events.index', ['company'=>$company]));
    }
    $event->delete();
    Flash::success('Evento borrado.');
    return redirect(route('companies.events.index', ['company'=>$company]));
	}
	public function formatDuration($duration)
	{
		// The base case is A:BB
		if(strlen($duration) == 4){
				return "00:0" . $duration;
		}
		// If AA:BB
		else if(strlen($duration) == 5){
				return "00:" . $duration;
		}   // If A:BB:CC
		else if(strlen($duration) == 7){
				return "0" . $duration;
		}
	}
	public function filterEvent_by(Company $company,Request $request)
  {
    $eventsFinal = null;
    $filter = $request->get('nameFiltrar');
    $filterState = $request->get('state');
    $filterDate = $request->get('initdate');
    $filterDateEnd = $request->get('enddate');
    $company = Company::where('id',  auth()->user()->company_id)->first();
    $listsStore = Store::all();
    if ($filter != null || $filterState != null || $filterDate != null || $filterDateEnd != null) {
      if ($filter != null) {
      $events = Event::where('company_id', auth()->user()->company_id)->where('name', 'LIKE', "%$filter%")->orderBy('state', 'asc')->paginate();
      }
      if ($filterState != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('state', $filterState)->orderBy('state', 'asc')->paginate();
      }
      if ($filterDate != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('initdate', 'LIKE', "%$filterDate%")->orderBy('state', 'asc')->paginate();
      }
      if ($filterDateEnd != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('enddate', 'LIKE', "%$filterDateEnd%")->orderBy('state', 'asc')->paginate();
      }
      if(count($events)==0){
        Flash::info('No se encontro ningun resultado.');
        return redirect(url()->previous());
      }
      return view('events.index', compact('events', 'listsStore'))->with('company', $company);
    }else {
    Flash::error('Ingrese un valor para generar la busqueda.');
    return redirect(url()->previous());
    }
  }
	//stores//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //ANCHOR Sucursales Compañia
  public function indexStore(Company $company, Request $request)
  {
    $stores = Store::with(['computers','computers.screens'])->where('company_id', $company->id)->get();
    return view('companies.stores.index', compact('company', 'stores'));
	}
	public function filterStore(Company $company, Request $request)
  {
		$stores = Store::with(['computers','computers.screens'])->where('company_id', $company->id);
		if($request->nameFilter==null&&$request->addressFilter==null){
			$stores = Store::with(['computers','computers.screens'])->where('company_id', $company->id)->get();
			Flash::error('Debes ingresar almenos un filtro para la busqueda.');
			return view('companies.stores.index', compact('company', 'stores'));
		}
		if (isset($request['nameFilter']) && !empty($request['nameFilter'])) {
			$stores = $stores->where('name', 'like', '%' . $request['nameFilter'] . '%')->get();
		}
		if (isset($request['addressFilter']) && !empty($request['addressFilter'])) {
      $stores = $stores->where('address', 'like', '%' . $request['addressFilter'] . '%')->get();
    }
    // $stores = Store::with(['computers','computers.screens'])->where('company_id', $company->id);
    // if (isset($request['nameFilter']) && !empty($request['nameFilter'])) {
    //   $stores = $stores->where('name', 'like', '%' . $request['nameFilter'] . '%');
    // } else if (isset($request['addressFilter']) && !empty($request['addressFilter'])) {
    //   $stores = $stores->where('address', 'like', '%' . $request['addressFilter'] . '%');
    // }
    // $stores = $stores->get();
    return view('companies.stores.index', compact('company', 'stores'));
  }
  public function showStore(Company $company, Store $store)
  {
    if (empty($company)) {
      Flash::error('Compañia no encontrada.');
      return redirect(route('companies.stores.index', compact('company')));
    }
    return view('companies.stores.show', compact('company', 'store'));
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
    return redirect(route('companies.stores.index', compact('company')));
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
    return redirect(route('companies.stores.index', $company));
  }
  public function destroyStore(Company $company, Store $store)
  {
    if (empty($store)) {
      Flash::error('tienda no encontrada');
      return redirect(route('companies.stores.index', $company));
    }
    $store->delete();
    Flash::success('Tienda borrada');
    return redirect(route('companies.stores.index', $company));
  }

  //SECTION Computadores Sucursales Compañia
  public function indexStoreComputers(Company $company, Request $request)
  {
    $stores = Store::where('company_id', $company->id);
    if (isset($request['nameFilter']) && !empty($request['nameFilter'])) {
      $stores = $stores->where('name', 'like', '%' . $request['nameFilter'] . '%');
    } else if (isset($request['addressFilter']) && !empty($request['addressFilter'])) {
      $stores = $stores->where('address', 'like', '%' . $request['addressFilter'] . '%');
    }
    $stores = $stores->get();
    return view('companies.stores.index', compact('company', 'stores'));
  }

  public function showStoreComputers($id)
  {
    $company = $this->companyRepository->find($id);
    if (empty($company)) {
      Flash::error('Compañia no encontrada.');
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
    return redirect(route('companies.stores.index', compact('company')));
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
    return redirect(route('companies.stores.index', $company));
  }
  public function destroyStoreComputers(Company $company, Store $store)
  {
    if (empty($store)) {
      Flash::error('tienda no encontrada');
      return redirect(route('companies.stores.index', $company));
    }
    $store->delete();
    Flash::success('Tienda borrada');
    return redirect(route('companies.stores.index', $company));
  }

  //SECTION Pantallas Computadores Sucursales Compañia
  public function indexStoreComputersScreens(Company $company, Request $request)
  {
    $stores = Store::where('company_id', $company->id);
    if (isset($request['nameFilter']) && !empty($request['nameFilter'])) {
      $stores = $stores->where('name', 'like', '%' . $request['nameFilter'] . '%');
    } else if (isset($request['addressFilter']) && !empty($request['addressFilter'])) {
      $stores = $stores->where('address', 'like', '%' . $request['addressFilter'] . '%');
    }
    $stores = $stores->get();
    return view('companies.stores.index', compact('company', 'stores'));
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
    return redirect(route('companies.stores.index', compact('company')));
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
    return redirect(route('companies.stores.index', $company));
  }
  public function destroyStoreComputersScreens(Company $company, Store $store)
  {
    if (empty($store)) {
      Flash::error('tienda no encontrada');
      return redirect(route('companies.stores.index', $company));
    }
    $store->delete();
    Flash::success('Tienda borrada');
    return redirect(route('companies.stores.index', $company));
	}
	//Pivot///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function indexPivot($id,Request $request)
  {
		$stores = Store::where('company_id',$id)->get();
		$pivots = ComputerPivot::where('company_id',$id)->paginate();
		return view('companies.pivots.index')->with('pivots',$pivots)->with('stores',$stores)->with('id',$id);
  }
  public function showPivot($company,$id)
  {
		$pivot = ComputerPivot::find($id);
		$onpivots = ComputerOnPivot::where('computer_pivot_id',$id)->paginate();
		$computers = Computer::whereHas('store', function ($query) use ($pivot) {
			$query->where('company_id', $pivot->company_id);
		})->paginate();
		return view('companies.pivots.show',['id' => $id])->with('pivot',$pivot)
		->with('onpivots',$onpivots)->with('computers',$computers);
  }

  public function createPivot($id)
  {
		$company = Company::find($id);
		$pivots = ComputerPivot::all();
		$companies = Company::all();
		$stores = store::where('company_id',$id)->get();
		return view('companies.pivots.create',['id' => $id])->with('pivots',$pivots)
		->with('companies',$companies)->with('stores',$stores)->with('company',$company)->with('id',$id);
  }

  public function storePivot($id,Request $request)
  {
		$validate = ComputerPivot::where('name',$request->name)->where('company_id',$request->company_id);
		if ($validate->count()!=0){
			Flash::error('Ese nombre de pivote ya existe.');
			return redirect(route('companies.pivots.create',[$id]));
		}
		$input = $request->all();
		ComputerPivot::create($input);
		Flash::success('Computador pivote agregado con exito.');
		return redirect(route('companies.pivots.index',[$id]));
  }

  public function editPivot($company,$id)
  {
		$companies = Company::all();
		$stores = store::all();
		$pivot = ComputerPivot::find($id);
		if (empty($pivot)) {
			Flash::error('Computador pivote no encontrado.');
			return redirect(route('companies.pivots.index',[$id]));
		}
		// Flash::success('Computador pivote editado correctamente.');
		return view('companies.pivots.edit',['id' => $id])->with('pivot',$pivot)
		->with('companies',$companies)->with('stores',$stores)->with('id',$id);
  }

  public function updatePivot($company,$id, Request $request)
  {
		$pivot = ComputerPivot::find($id);
		if (empty($pivot)) {
			Flash::error('Computador pivote no encontrado');
			return redirect(route('pivots.index'));
		}
		ComputerPivot::find($id)->update($request->all());
		Flash::success('Computador pivote editado.');
		return redirect(route('companies.pivots.index',[$company]));
  }
  public function destroyPivot($id)
  {
		$pivot = ComputerPivot::find($id);
		if (empty($pivot)) {
			Flash::error('Computador pivote no encontrado.');
			return redirect(route('companies.pivots.index',[$pivot->company_id]));
		}
		$pivot->delete();
		Flash::success('Computador pivote borrado.');
		return redirect(route('companies.pivots.index',[$pivot->company_id]));
	}
	public function storeOnpivot($company,$id,Request $request)
  {
		$validate = ComputerOnPivot::where('computer_id',$request->computer_id)->where('computer_pivot_id',$request->computer_pivot_id);
		if($validate->count()!=0){
			Flash::error('Computador ya asignado en este pivote.');
			return redirect()->route('companies.pivots.show', ['company'=>$company,$id]);
		}
		$input = $request->all();
		ComputerOnPivot::create($input);
		Flash::success('Computador agregado con exito.');
		return redirect()->route('companies.pivots.show', ['company'=>$company,$id]);
	}
	public function destroyOnpivot($company,$id)
  {
		$onpivot = ComputerOnPivot::find($id);
		$pivot = ComputerPivot::find($onpivot->computer_pivot_id);
		if (empty($onpivot)) {
			Flash::error('Computador no encontrado');
			return redirect()->route('companies.pivots.show', ['company'=>$company,$pivot->id]);
		}
		$onpivot->delete();
		Flash::success('Asignacion eliminada.');
		return redirect()->route('companies.pivots.show', ['company'=>$company,$pivot->id]);
	}
	public function filterPivot_by($id,Request $request)
	{

		$companies = Company::all();
		$stores = Store::where('company_id',$id)->get();
		$pivots = ComputerPivot::where('company_id',$id)->paginate();
		if($request->nameFiltrar==null&&$request->codeFiltrar==null&&$request->store==null){
			Flash::error('Debes ingresar almenos un filtro para la busqueda.');
			return redirect(route('companies.pivots.index',[$id]));
		}
		if($request->store!=null){
			$pivots = ComputerPivot::where('company_id',$id)->where('location',$request->store)->paginate();
		}
		if($request->codeFiltrar!=null){
			$pivots = ComputerPivot::where('company_id',$id)->where('code','like',"%$request->codeFiltrar%")->paginate();
		}
		if($request->nameFiltrar!=null){
			$pivots = ComputerPivot::where('company_id',$id)->where('name','like',"%$request->nameFiltrar%")->paginate();
		}
		if($pivots->count()==0){
			Flash::error('No se encontro ningun resultado.');
		}
		return view('companies.pivots.index')->with('pivots',$pivots)->with('companies',$companies)->with('stores',$stores)->with('id',$id);
	}
	//Computers///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function indexComputer(Company $company,Request $request)
	{
		$types= AccessType::all();
		$stores = Store::all();
		$computers = Computer::whereHas('store', function ($query) use ($company) {
			$query->where('company_id', $company->id);
		})->paginate();
		return view('companies.computers.index',['company' => $company], compact('stores', 'types'))
		->with('computers', $computers);
	}
	public function showComputer(Company $company,Computer $computer)
	{
		// $computers = Computer::where('store_id', $id)->paginate();
		$screens = Screen::where('computer_id',$computer->id)->get();
		return view('companies.computers.show',['company' => $company])->with('computer',$computer)->with('screens',$screens);
	}
	public function createComputer(Company $company)
	{
		$types= AccessType::all();
		$lists = Company::all();
		$stores = Store::where('company_id',$company->id)->get();
		$companies = Company::all();
		return view('companies.computers.create',['company' => $company], compact('companies', 'stores', 'lists','types'));
	}

	//Request de creacion (POST)
	public function storeComputer(Company $company,Request $request)
	{
		$input = $request->all();
		Computer::create($input);
		Flash::success('Computador agregado correctamente.');
		return redirect(route('companies.computers.index',['company' => $company]));
	}
	public function createScreen($id)
	{
		$computer = Computer::where('id', $id)->get();
		return view('screen.create')->with('computer',$computer);
	}
	public function editComputer(Company $company,Computer $computer,Store $store)
	{
		$types = AccessType::all();
		$stores = Store::where('company_id', $company->id)->get();
		if (empty($computer)) {
			Flash::error('Computador no encontrado');
			return redirect(route('companies.computers.index',['company' => $company]));
		}
		return view('companies.computers.edit',['company' => $company], compact('stores', 'types'))->with('computer', $computer)->with('company', $company);
	}
	//Request de editar (POST)
	public function updateComputer(Company $company,$id, Request $request)
	{
		$computer = Computer::find($id);
		if (empty($computer)) {
			Flash::error('Computador no encontrado.');
			return redirect(route('companies.computers.index',['company' => $company]));
		}
		Computer::find($id)->update($request->all());
		Flash::success('Computador editado');
		return redirect(route('companies.computers.index',['company' => $company]));
	}
	public function destroyComputer(Company $company,$id)
	{
		$computer = Computer::find($id);
		if (empty($computer)) {
			Flash::error('Computador no encontrado.');
			return redirect(route('companies.computers.index',['company' => $company]));
		}
		$computer->delete();
		Flash::success('Computador borrado.');
		return redirect(route('companies.computers.index',['company' => $company]));
	}
	public function filter_computers(Company $company,Request $request)
	{
		$types= AccessType::all();
		$stores = Store::all();
		$computers = Computer::whereHas('store', function ($query) use ($company) {
			$query->where('company_id', $company->id);
			});
		if($request->codeFiltrar==null&&$request->type==null&&$request->store==null){
			Flash::error('Debes ingresar almenos un filtro para la busqueda.');
			return redirect(route('companies.computers.index',[$company]));
		}
		if($request->store!=null){
			$computers->Where('store_id', $request->store);
		}

		if($request->type!=null){
			$computers->Where('type_id', $request->type);
		}
		if($request->codeFiltrar!=null){
			$computers->Where('code','like', "%$request->codeFiltrar%");
		}
		$computers = $computers->paginate();
		if($computers->count()==0){
			$computers = Computer::whereHas('store', function ($query) use ($company) {
			$query->where('company_id', $company->id);
			})->paginate();
			Flash::info('No se encontro ningun resultado.');
		}
		return view('companies.computers.index',['company' => $company], compact('companies', 'stores', 'lists', 'types'))->with('computers', $computers);
	}
	//Screens///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function storeScreen(Company $company,Computer $computer,Request $request)
	{
		$input = $request->all();
		Screen::create($input);
		Flash::success('Pantalla agregada correctamente.');
		return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
	}
	public function editScreen(Company $company,Computer $computer, Screen $screen)
	{
		return view('companies.computers.editScreen',['company' => $company,'computer'=>$computer])->with('screen',$screen);
	}
	public function showScreen(Company $company,Computer $computer, Screen $screen)
	{
		//buscamos los eventos compatibles con la pantalla
		$contents = Content::whereHas('event', function ($query) use($company) {
			$query->where('company_id', $company->id)->where('enddate','>=',\Carbon\Carbon::now());
		})->where('width',$screen->width)->where('height',$screen->height)->get();

		$list=[];
		foreach($contents as $content){
			array_push($list,$content->event->id);
		}
		//extraemos los eventos compatibles
		$events= Event::find($list);
		//eventos asignados activos
		$eventAssigns = EventAssignation::whereHas('content', function ($query) {
			$query->whereHas('event', function ($query) {
				 $query->where('enddate','>=',\Carbon\Carbon::now());
			});
		})->where('screen_id',$screen->id)->where('state',1)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//eventos asignados inactivos
		$eventInactives = EventAssignation::whereHas('content', function ($query) {
			$query->whereHas('event', function ($query) {
				$query->where('enddate','>=',\Carbon\Carbon::now());
			});
		})->where('screen_id',$screen->id)->where('state',0)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		return view('companies.computers.showScreen',['company' => $company,'computer'=>$computer])
		->with('screen',$screen)->with('events', $events)->with('eventAssigns', $eventAssigns)
		->with('eventInactives', $eventInactives);
	}
	public function updateScreen(Company $company,Computer $computer, Request $request)
	{
		$screen = Screen::find($request->id);
		if (empty($screen)) {
			Flash::error('Pantalla no encontrada.');
			return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
		}
		$screen->update($request->all());
		Flash::success('Pantalla editada correctamente.');
		return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
	}
	public function destroyScreen(Company $company,Computer $computer, Screen $screen)
	{
		$screenDelete = Screen::find($screen->id);
		$screenDelete->delete();
		Flash::success('Pantalla borrada.');
		return redirect(url()->previous());
	}
	public function changeStatusScreen(Company $company,Computer $computer, Screen $screen,Request $request)
	{
		$screen->state = $request['state'];
		if (empty($request)) {
			Flash::error('Error');
			return redirect(url()->previous());
		}
		$screen->save();
		Flash::success('Estado actualizado');
		return redirect(url()->previous($screen));
	}
	public function eventAssignScreen(Company $company,Computer $computer, Screen $screen, Request $request)
	{
		$request->merge(['slug' => Str::slug($request['name'])]);
		$event = Event::find($request->event_id);
		$contents = Content::where('event_id',$request->event_id)
		->where('width',$screen->width)
		->where('height',$screen->height)
		->get();
		if($contents->count()!=1){
			foreach($contents AS $content){
				$request->merge([
				"screen_id"=> $screen->id,
				"state"=>$event->state,
				"content_id"=>$content->id,
				]);
				$input = $request->all();
				EventAssignation::create($input);
			}
		}else{
			$request->merge([
				"screen_id"=> $screen->id,
				"state"=>$event->state,
				"content_id"=>$contents[0]->id,
				]);
				$input = $request->all();
				EventAssignation::create($input);
				$screen->version=$screen->version+1;
				$screen->save();
		}
		Flash::success('Evento asignado exitosamente');
		return redirect(url()->previous($screen));
	}
	public function changeOrderScreen(Company $company,Computer $computer, Screen $screen,Request $request)
	{
		// validaciones
		if($request->neworder==null){
			Flash::error('Debes ingresar un nuevo Nº de orden.');
			return redirect(url()->previous());
		}
		if($request->screen==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		if($request->id==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		//traemos la asignacion
		$assign=EventAssignation::find($request->id);
		//asignamos los nuevos valores y guardamos
		$assign->order = $request->neworder;
		$screen->version = $screen->version+1;
		$assign->save();
		$screen->save();
		Flash::success('Se ha cambiado el Nº de orden correctamente.');
		return redirect(url()->previous());
	}
	public function cloneEventScreen(Company $company,Computer $computer, Screen $screen,Request $request)
	{
		// extraemos los datos del elemento original
		$input = $request->all();
		//cambiamos version en pantalla
		$screen->version = $screen->version+1;
		$screen->save();
		//creamos el nuevo elemento clonado
		EventAssignation::create($input);
		Flash::success('Se ha clonado el elemento correctamente.');
		return redirect(url()->previous());
	}
}
