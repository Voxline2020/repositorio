<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Store;
use App\Models\Computer;
use App\Models\ComputerPivot;
use App\Models\ComputerOnPivot;
use Auth;
use App\Repositories\CompanyRepository;
use Flash;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str as Str;


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
    $companies = Company::paginate();
    return view('companies.index')
      ->with('companies', $companies)->with('computers', $computers);
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

  public function dash()
  {
    $companies = $this->companyRepository->all();
    return view('companies.index')
      ->with('companies', $companies);
  }

  //ANCHOR Eventos Compañia
  public function indexEvent(Company $company, Request $request)
  {
    $events = Event::with('contents')->where('company_id', $company->id)->paginate();
    $lists = Event::with('contents')->where('company_id', $company->id)->paginate();
    $listsStore = Store::all();
    return view('companies.events.index', compact('lists', 'listsStore', 'events', 'company'));
  }
  public function showEvent(Company $company, Event $event)
  {
    if (empty($company) || empty($event)) {
      Flash::error('Compañia no encontrada');
      return redirect(route('companies.events.index', $company));
    }
    return view('companies.events.show', compact('company','event'));
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
		dd($request);
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
      Flash::error('Compañia no encontrada');
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
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	public function filter_by($id,Request $request)
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
}
