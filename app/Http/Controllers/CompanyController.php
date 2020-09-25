<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Str as Str;
use App\Models\EventAssignation;
use App\Models\ComputerOnPivot;
use App\Models\ComputerPivot;
use Illuminate\Http\Request;
use App\Models\AccessType;
use App\Models\DeviceType;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Company;
use App\Models\Device;
use App\Models\Store;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Flash;
use Auth;


class CompanyController extends AppBaseController
{
  /** @var  CompanyRepository */
  private $companyRepository;

  public function __construct(CompanyRepository $companyRepo)
  {
		$this->middleware('auth');
		$this->middleware('admin');
    $this->companyRepository = $companyRepo;
  }
  //mostrar compañias
  public function index(Request $request)
  {
		if(Auth::user()->hasRole('Terreno')){
			return redirect(route('companies.terreno.index'));
		}
		$computers = Computer::with(['store'])->get();
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
		$now = Carbon::now()->format('Y/m/d H:i');
		$old_check = 0;
		$events = Event::with('contents')
		->where('company_id', $company->id)
		->where('enddate','>=',$now)
		->orderBy('state', 'asc')
		->paginate();
    return view('companies.events.index', compact('lists', 'listsStore', 'events', 'company'))->with('old_check', $old_check);
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
		$assignsList = [];
		foreach ($contentsList AS $id) {
			$eas = EventAssignation::where('content_id',$id)->get();
			foreach($eas as $ea){
				array_push($assignsList, $ea->id);
			}
		};
		$eventAssigns = EventAssignation::find($assignsList);
		//extraemos las pantallas de los contenidos asignados
		$list = [];
		foreach ($eventAssigns AS $asign) {
			array_push($list, $asign->device_id);
		};
		//extraemos las pantallas
		$devices= Device::find($list);
		//stores y types
		$stores = Store::where('company_id',$company->id)->get();
		$types = DeviceType::all();
		return view('companies.events.show',['company' => $company,'event'=>$event], compact('company','event'))
		->with('stores',$stores)
		->with('types',$types)
		->with('devices',$devices);
	}else{
		$stores = Store::where('company_id',$company->id)->get();
		$types = DeviceType::all();
		$devices= $event->contents;
		return view('companies.events.show',['company' => $company,'event'=>$event], compact('company','event'))
		->with('stores',$stores)
		->with('types',$types)
		->with('devices',$devices);
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
			Flash::success('Evento agregado exitosamente.');
			return redirect(route('companies.events.edit',['company'=>$company,'event'=>$event[0]]));
		}
    Flash::error('Error al agregar el evento.');
		return redirect(route('companies.events.index', $company));
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
		foreach ($event->contents as $content) {
			foreach ($content->eventAssignations as $eventAssignation) {
				if($event->state == 1){
					$eventAssignation->device->version = $eventAssignation->device->version+1;
					$eventAssignation->device->save();
				}
				$eventAssignation->delete();
			}
			$content->delete();
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
	public function destroyEventAssign(Company $company,Computer $computer,EventAssignation $assign)
  {
    $assign->delete();
		Flash::success('Evento desasignado.');
		$device = Device::find($assign->device_id);
		$device->version = $device->version+1;
		$device->save();
    return redirect()->route('companies.computers.showDevice', ['company'=>$company,'computer'=>$computer,'device'=>$device]);
	}
	public function filterEvent_by(Company $company,Request $request)
  {
		if($request->nameFiltrar==null&&$request->state==null&&$request->initdate==null&&$request->enddate==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		$old_check = $request->old_check;
		$initdate = Carbon::parse(str_replace('/', '-',$request->initdate))->format('Y-m-d H:i');
		$enddate = Carbon::parse(str_replace('/', '-',$request->enddate))->format('Y-m-d H:i');
		$events = Event::with('contents')->where('company_id', $company->id);
		if($request->nameFiltrar!=null){
			$events->Where('name','like',"%$request->nameFiltrar%");
		}
		if($request->state!=null){
			$events->Where('state',$request->state);
		}
		if($request->initdate!=null){
			$events->where('initdate','>=',$initdate);
		}
		if($request->enddate!=null){
			$events->where('enddate','<=',$enddate);
		}
		$events = $events->paginate();
		if($events->count()==0){
			Flash::info('No se encontro ningun resultado.');
			return redirect(url()->previous());
		}
		return view('companies.events.index', compact('events', 'company'))->with('old_check', $old_check);
	}
	public function view_old(Company $company,Request $request)
	{
		$old_check = 1;
		$events = Event::where('company_id',$company->id)
		->orderBy('state', 'asc')
		->paginate();
    return view('companies.events.index', compact('events'))->with('company', $company)->with('old_check', $old_check);
	}
	//stores//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //ANCHOR Sucursales Compañia
  public function indexStore(Company $company, Request $request)
  {
    $stores = Store::with(['computers','computers.devices'])->where('company_id', $company->id)->get();
    return view('companies.stores.index', compact('company', 'stores'));
	}
	public function filterStore(Company $company, Request $request)
  {
		$stores = Store::with(['computers','computers.devices'])->where('company_id', $company->id);
		if($request->nameFilter==null&&$request->addressFilter==null){
			$stores = Store::with(['computers','computers.devices'])->where('company_id', $company->id)->get();
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
		if($request->name==null){
			Flash::error('El campo "nombre" es requerido.');
		}
		if($request->address==null){
			Flash::error('El campo "Direccion" es requerido.');
		}
		if($request->name!=null&&$request->address!=null){
			$input = $request->all();
			$store = Store::create($input);
			Flash::success('Tienda agregada con exito.');
			return redirect(route('companies.stores.index', compact('company')));
		}
		return redirect(url()->previous());
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
		})->get();
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
		$validatename = ComputerPivot::where('name',$request->name)->where('company_id',$request->company_id);
		$validatecode = ComputerPivot::where('code',$request->code)->where('company_id',$request->company_id);
		if ($validatename->count()!=0){
			Flash::error('Ese nombre de pivote ya existe.');
			return redirect(route('companies.pivots.create',[$id]));
		}
		if ($validatecode->count()!=0){
			Flash::error('Este codigo ya ha sido asignado a otro pivote.');
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
		if(empty($request->computer_id)){
			$count = 0;
		}else{
			$count = count($request->computer_id);
		}
		if($count==0){
			Flash::error('Debes seleccionar almenos 1 computador.');
			return redirect()->route('companies.pivots.show', ['company'=>$company,$id]);
		}
		foreach($request->computer_id AS $computer_id){
			$validate = ComputerOnPivot::where('computer_id',$computer_id)
			->where('computer_pivot_id',$request->computer_pivot_id);
			if($validate->count()!=0){
				$computer = Computer::find($computer_id);
				Flash::error('Computador '.$computer->code.' ya asignado en este pivote.');
			}else{
				$computer = Computer::find($computer_id);
				$request->merge([
					'computer_id' => $computer_id,
				]);
				$input = $request->all();
				ComputerOnPivot::create($input);
				Flash::success('Computador '.$computer->code.' agregado con exito.');
			}
		}
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
		$types = DeviceType::all();
		$devices = Device::where('computer_id',$computer->id)->get();
		return view('companies.computers.show',['company' => $company])
		->with('computer',$computer)
		->with('devices',$devices)
		->with('types',$types);
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
		if($request->code==null){
			Flash::error('El campo "codigo" es requerido.');
			return redirect(url()->previous());
		}
		if($request->location==null){
			Flash::error('El campo "Ubicacion" es requerido.');
			return redirect(url()->previous());
		}
		if($request->store_id==null){
			Flash::error('El campo "Sucursal" es requerido.');
			return redirect(url()->previous());
		}
		if($request->type_id==null){
			Flash::error('El campo "Tipo" es requerido.');
			return redirect(url()->previous());
		}
		$computers = Computer::where('code',$request->code)->get();
		if($computers->count()!=0){
			Flash::error('Este codigo ya ha sido asignado a otro computador.');
			$types= AccessType::all();
			$lists = Company::all();
			$stores = Store::where('company_id',$company->id)->get();
			$companies = Company::all();
			return view('companies.computers.create',['company' => $company], compact('companies', 'stores', 'lists','types'));
		}
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
	//Devices///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function storeDevice(Company $company,Computer $computer,Request $request)
	{
		// dd($request);
		if($request->name==null){
			Flash::error('El campo "nombre" es requerido.');
		}
		if($request->width==null){
			Flash::error('El campo "ancho" es requerido.');
		}
		if($request->height==null){
			Flash::error('El campo "alto" es requerido.');
		}
		if($request->type_id==null){
			Flash::error('El campo "tipo" es requerido.');
		}
		if($request->imei==null){
			Flash::error('El campo "imei" es requerido.');
		}
		if($request->name!=null&&$request->width!=null&&$request->height!=null){
			$input = $request->all();
			Device::create($input);
			Flash::success('Dispositivo agregado correctamente.');
			return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
		}
		return redirect(url()->previous());
	}
	public function editDevice(Company $company,Computer $computer, Device $device)
	{
		$types = DeviceType::all();
		return view('companies.computers.editDevice',['company' => $company,'computer'=>$computer])
		->with('device',$device)
		->with('types',$types);
	}
	public function showDevice(Company $company,Computer $computer, Device $device)
	{
		//buscamos los eventos compatibles con la pantalla
		$contents = Content::whereHas('event', function ($query) use($company) {
			$query->where('company_id', $company->id)->where('enddate','>=',\Carbon\Carbon::now());
		})->where('width',$device->width)->where('height',$device->height)->get();

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
		})->where('device_id',$device->id)->where('state',1)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//eventos asignados inactivos
		$eventInactives = EventAssignation::whereHas('content', function ($query) {
			$query->whereHas('event', function ($query) {
				$query->where('enddate','>=',\Carbon\Carbon::now());
			});
		})->where('device_id',$device->id)->where('state',0)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		return view('companies.computers.showDevice',['company' => $company,'computer'=>$computer])
		->with('device',$device)->with('events', $events)->with('eventAssigns', $eventAssigns)
		->with('eventInactives', $eventInactives);
	}
	public function updateDevice(Company $company,Computer $computer, Request $request)
	{
		$device = Device::find($request->id);
		if (empty($device)) {
			Flash::error('Dispositivo no encontrado.');
			return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
		}
		$device->update($request->all());
		Flash::success('Dispositivo editado correctamente.');
		return redirect(route('companies.computers.show',['company' => $company,'computer'=>$computer]));
	}
	public function destroyDevice(Company $company,Computer $computer, Device $device)
	{
		$deviceDelete = Device::find($device->id);
		$deviceDelete->delete();
		Flash::success('Dispositivo borrado.');
		return redirect(url()->previous());
	}
	public function changeStatusDevice(Company $company,Computer $computer, Device $device,Request $request)
	{
		$device->state = $request['state'];
		if (empty($request)) {
			Flash::error('Error');
			return redirect(url()->previous());
		}
		$device->state = $request['state'];
		$device->save();
		Flash::success('Estado actualizado');
		return redirect(url()->previous($device));
	}
	public function eventAssignDevice(Company $company,Computer $computer, Device $device, Request $request)
	{
		$request->merge(['slug' => Str::slug($request['name'])]);
		$events = Event::find($request->event_id);

		foreach($events AS $event){
			$contents = Content::where('event_id',$event->id)
			->where('width',$device->width)
			->where('height',$device->height)
			->get();
			// if($contents->count()!=1){
			foreach($contents AS $content){
				$count_assigns = EventAssignation::where('device_id',$device->id)->where('state',1)->count()+1;
				$request->merge([
				"device_id"=> $device->id,
				"state"=>$event->state,
				"content_id"=>$content->id,
				"order"=>$count_assigns,
				]);
				$device->version=$device->version+1;
				$device->save();
				$input = $request->all();
				EventAssignation::create($input);
			}
			Flash::success('Evento "'.$event->name.'" asignado exitosamente');
		}
		return redirect(url()->previous($device));
	}
	public function changeOrderDevice(Company $company,Computer $computer, Device $device,Request $request)
	{
		// validaciones
		if($request->neworder==null){
			Flash::error('Debes ingresar un nuevo Nº de orden.');
			return redirect(url()->previous());
		}
		if($request->device==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		if($request->id==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		//llamado de objeto inicial
		$objIni = EventAssignation::find($request->id);
		//si la nueva posicion y la posicion actual son iguales
		if ($request->neworder == $objIni->order) {
			Flash::error('La nueva posicion no puede ser igual a la actual.');
			return redirect(url()->previous());
		}
		//si la nueva posicion excede el rango de elementos
		$countObjs = EventAssignation::where('device_id',$device->id)->get();
		if ($countObjs->count() < $request->neworder) {
			Flash::error('La nueva posicion no puede ser mayor a la cantidad total de elementos.');
			return redirect(url()->previous());
		}
		//si la nueva posicion es menor de 1
		if ($request->neworder < 1) {
			Flash::error('La nueva posicion no puede ser menor que el primer elemento.');
			return redirect(url()->previous());
		}
		//llamado coleccion de objs intermedios
		if($objIni->order < $request->neworder){
			$listobjs = EventAssignation::where('device_id',$device->id)
			->where('order','<=',$request->neworder)
			->where('order','>',$objIni->order)
			->orderby('order','ASC')
			->where('state',1)
			->get();
		}else if($objIni->order > $request->neworder){
			$listobjs = EventAssignation::where('device_id',$device->id)
			->where('order','>=',$request->neworder)
			->where('order','<',$objIni->order)
			->orderby('order','ASC')
			->where('state',1)
			->get();
		}
		//intercambio de orden y guardado
		$order = $objIni->order;
		$neworder = $request->neworder;
		$objIni->order = $neworder;
		foreach($listobjs as $objs){
			if($order < $neworder){
				$objs->order = $order;
				$order = $order+1;
			}else if($order > $neworder){
				$neworder = $neworder+1;
				$objs->order = $neworder;
			}
			$objs->save();
		}
		$objIni->save();
			//cambiamos version en pantalla
			$device=Device::find($device->id);
			$device->version = $device->version+1;
			$device->save();
		Flash::success('Cambio de orden realizado.');
		return redirect(url()->previous());
	}
	public function cloneEventDevice(Company $company,Computer $computer, Device $device,Request $request)
	{
		//asignamos el order para el clon
		$count_assigns = EventAssignation::where('device_id',$device->id)->where('state',1)->count()+1;
		$request->merge([
			"order"=>$count_assigns,
		]);
		// extraemos los datos del elemento original
		$input = $request->all();
		//cambiamos version en pantalla
		$device->version = $device->version+1;
		$device->save();
		//creamos el nuevo elemento clonado
		EventAssignation::create($input);
		Flash::success('Se ha clonado el elemento correctamente.');
		return redirect(url()->previous());
	}
	public function filter_device(Company $company,Request $request)
	{
		$event_id = $request->event_id;
		$name = $request->nameFiltrar;
		$store = $request->store_id;
		$state = $request->state;
		$type = $request->type_id;
		if($name==null && $state==null && $store==null && $type==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		//comprobamos que el evento tenga contenido
		$event = Event::find($event_id);
		if($event->contents->count()!=0){
			//obtenemos los contenidos del evento
			$contentsList = [];
			foreach ($event->contents AS $content) {
				array_push($contentsList, $content->id);
			};
			//traemos las asignaciones de eventos que coincidan con los contenidos del evento que estamos revisando
			$assignsList = [];
			foreach ($contentsList AS $id) {
				$eas = EventAssignation::where('content_id',$id)->get();
				foreach($eas as $ea){
					array_push($assignsList, $ea->id);
				}
			};
			$eventAssigns = EventAssignation::find($assignsList);
			//extraemos los id de dispositivo de los contenidos asignados
			$list = [];
			foreach ($eventAssigns AS $asign) {
				array_push($list, $asign->device_id);
			};
		}
		//filtros
		if($name != null){
			$devices = Device::where('name','LIKE',"%$name%")->find($list);
		}
		if($state != null){
			$devices = Device::whereHas('computer', function ($query) use ($company) {
				$query->whereHas('store', function ($query) use ($company){
					$query->where('company_id', $company->id);
				});
			})->where('state', $state )->find($list);
		}
		if($store != null){
			$devices = Device::whereHas('computer', function ($query) use ($store) {
				$query->whereHas('store', function ($query) use ($store){
					$query->where('id', $store);
				});
			})->find($list);
		}
		if($type != null){
			$devices = Device::where('type_id',$type)->find($list);
		}
		if($devices->count()==0){
			Flash::info('No se ha encontrado ningun resultado.');
			return redirect(url()->previous());
		}
		$stores = Store::where('company_id',$company->id)->get();
		$types = DeviceType::all();
		return view('companies.events.show',['company'=>$company])
		->with('devices',$devices)
		->with('stores',$stores)
		->with('types',$types)
		->with('event',$event);
	}
	//Terreno///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function indexTerreno(Request $request)
	{
		$computers = Computer::with(['store'])->get();
		$pivots = ComputerPivot::all();
		$verifyUser = User::where('email',Auth::user()->email)->get();
		$list = [];
		foreach($verifyUser AS $user){
			ARRAY_PUSH($list,$user->company_id);
		}
		$companies = Company::find($list);
    // $companies = Company::where('id', auth()->user()->company_id)->paginate();
    return view('companies.terreno.index')
      ->with('companies', $companies)->with('computers', $computers)->with('pivots', $pivots);
	}
}
