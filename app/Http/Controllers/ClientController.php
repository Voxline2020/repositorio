<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str as Str;
use App\Models\Screen;
use App\Models\Content;
use App\Models\Company;
use App\Models\Computer;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\Store;
use Carbon\Carbon;
use Flash;
use Response;

class ClientController extends Controller
{
    	/** @var  EventRepository */
	private $eventRepository;

	public function __construct(EventRepository $eventRepo)
	{
		$this->middleware('auth');
		$this->eventRepository = $eventRepo;
	}
	//mostrar compañias
	public function index(Request $request)
	{
		$events = $this->eventRepository->all()->where('company_id', Auth::user()->company_id);
		$screens = Screen::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'asc')->paginate();
		$screensCount = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$error = $request->session()->get('error');
		if(!empty($error)){
			Flash::error($error);
		}
		return view('client.index',compact('screens','events','screensCount'));
	}
	public function show($id)
	{
		//filtramos la pantalla que queremos ver con el id
		if (Auth::user()->hasRole('Administrador')){
			$screen = Screen::whereHas('computer', function ($query) {
				$query->whereHas('store', function ($query) {
				});
			})->find($id);
		}else {
			$screen = Screen::whereHas('computer', function ($query) {
				$query->whereHas('store', function ($query) {
					$query->where('company_id', Auth::user()->company_id);
				});
			})->find($id);
		}
		//ahora buscamos los eventos compatibles con la pantalla
		$contents = Content::whereHas('event', function ($query) {
			$query->where('company_id', Auth::user()->company_id)->where('enddate','>=',\Carbon\Carbon::now());
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
		})->where('screen_id',$id)->where('state',1)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//eventos asignados inactivos
		$eventInactives = EventAssignation::whereHas('content', function ($query) {
			$query->whereHas('event', function ($query) {
				$query->where('enddate','>=',\Carbon\Carbon::now());
			});
		})->where('screen_id',$id)->where('state',0)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();

		return view('client.screen.show')->with('screen',$screen)->with('events', $events)->with('eventAssigns', $eventAssigns)->with('eventInactives', $eventInactives);
	}
	public function filter_by_name(Request $request)
	{
		$name = $request->nameFiltrar;
		$state = $request->state;
		$events = $this->eventRepository->all()->where('company_id', Auth::user()->company_id);
		$screensCount = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		if($name != null || $state != null){

			if($name != null){
				$screens = Screen::whereHas('computer', function ($query) {
					$query->whereHas('store', function ($query) {
						$query->where('company_id', Auth::user()->company_id);
					});
				})->where('name','LIKE',"%$name%")->orderBy('state', 'asc')->paginate();
			}
			if($state != null){
				$screens = Screen::whereHas('computer', function ($query) {
					$query->whereHas('store', function ($query) {
						$query->where('company_id', Auth::user()->company_id);
					});
				})->where('state', $state )->orderBy('state', 'asc')->paginate();
			}
			if(count($screens)==0){
				Flash::info('No se encontro ningun resultado.');
				return redirect(url()->previous());
			}
			if($name != null && $state != null){
				$screens = Screen::whereHas('computer', function ($query) {
					$query->whereHas('store', function ($query) {
						$query->where('company_id', Auth::user()->company_id);
					});
				})->where('name','LIKE',"%$name%")->where('state', $state )->orderBy('state', 'asc')->paginate();
			}
			return view('client.index',compact('screens','events','screensCount'));
		}else{
			Flash::error('Ingrese un valor para generar la busqueda.');
    		return redirect(url()->previous());
		}
	}
	//Events///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function indexEvent(Request $request)
  {
    $company = Company::where('id',  auth()->user()->company_id)->first();
    // $events = $this->eventRepository->all();
    $events = Event::where('company_id',  auth()->user()->company_id)->orderBy('state', 'asc')->paginate();
    // $lists = Event::where('company_id', $id);
    $listsStore = Store::all();
    return view('client.events.index', compact('events', 'listsStore'))->with('company', $company);

	}
	public function showEvent(Event $event)
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
			return view('client.events.show', compact('event'))->with('screens',$screens);
		}else{
			$screens= $event->contents;
    	return view('client.events.show', compact('event'))->with('screens',$screens);
		}

	}
	public function createEvent()
  {
		return view('client.events.create');
		// return redirect(route('clients.index'));
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
			return redirect(route('clients.events.index'));
		}
    Flash::error('Error al agregar el evento.');
		return redirect(route('clients.events.index'));
  }
  public function editEvent(Event $event )
  {
    return view('client.events.edit')->with('event', $event);
  }
  public function updateEvent(Event $event,Request $request)
  {
		$request->merge([
				"initdate"=> Carbon::createFromFormat('d/m/Y H:i',$request["initdate"])->toDateTimeString(),
				"enddate"=> Carbon::createFromFormat('d/m/Y H:i',$request["enddate"])->toDateTimeString(),
				]);
    $event->update($request->all());
    Flash::success('Evento editado exitosamente.');
		return redirect()->route('clients.events.edit', ['event'=>$event]);
  }
  public function destroyEvent(Event $event)
  {
    if (empty($event->id)) {
      Flash::error('Evento no encontrado.');
      return redirect(route('clients.events.index'));
    }
    $event->delete();
    Flash::success('Evento borrado.');
    return redirect(route('clients.events.index'));
	}
	public function filterEvent_by(Request $request)
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
      return view('client.events.index', compact('events', 'listsStore'))->with('company', $company);
    }else {
    Flash::error('Ingrese un valor para generar la busqueda.');
    return redirect(url()->previous());
		}
	}
	public function destroyEventAssign(EventAssignation $assign)
  {
    $assign->delete();
    Flash::success('Evento desasignado.');
    return redirect()->route('clients.show', ['id'=>$assign->screen_id]);
	}
	public function filter_screen(Request $request)
	{
		$event_id = $request->event_id;
		$name = $request->nameFiltrar;
		$state = $request->state;
		$sector = $request->sector;
		$floor = $request->floor;
		$type = $request->type;
		if($name==null && $state==null && $sector==null && $floor==null && $type==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		//obtenemos los contenidos del evento
		$event = Event::find($event_id);
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
		// $screens= screen::find($list);
		//filtros
		$screens=null;
		if($name != null){
			$screens= Screen::where('name','LIKE',"%$name%")->orderBy('state', 'asc')->find($list);
		}
		if($state != null){
			$screens = Screen::whereHas('computer', function ($query) {
				$query->whereHas('store', function ($query) {
					$query->where('company_id', Auth::user()->company_id);
				});
			})->where('state', $state )->find($list);
		}
		if($sector != null){
			$screens= Screen::where('sector','LIKE',"%$sector%")->orderBy('state', 'asc')->find($list);
		}
		if($floor != null){
			$screens= Screen::where('floor','LIKE',"%$floor%")->orderBy('state', 'asc')->find($list);
		}
		if($type != null){
			$screens= Screen::where('type','LIKE',"%$type%")->orderBy('state', 'asc')->find($list);
		}
		// dd($screens->count());
		if($screens->count()==0){
			Flash::info('No se ha encontrado ningun resultado.');
			return redirect(url()->previous());
		}
		return view('client.events.show')->with('screens',$screens)->with('event',$event);




		// return view('client.events.show');


													// if($name != null || $state != null){

													// 	if($name != null){
													// 		$screens = Screen::whereHas('computer', function ($query) {
													// 			$query->whereHas('store', function ($query) {
													// 				$query->where('company_id', Auth::user()->company_id);
													// 			});
													// 		})->where('name','LIKE',"%$name%")->orderBy('state', 'asc')->paginate();
													// 	}
													// 	if($state != null){
													// 		$screens = Screen::whereHas('computer', function ($query) {
													// 			$query->whereHas('store', function ($query) {
													// 				$query->where('company_id', Auth::user()->company_id);
													// 			});
													// 		})->where('state', $state )->orderBy('state', 'asc')->paginate();
													// 	}
													// 	if(count($screens)==0){
													// 		// Flash::info('No se encontro ningun resultado.');
													// 		// // return redirect(url()->previous());
													// 	}
													// 	if($name != null && $state != null){
													// 		$screens = Screen::whereHas('computer', function ($query) {
													// 			$query->whereHas('store', function ($query) {
													// 				$query->where('company_id', Auth::user()->company_id);
													// 			});
													// 		})->where('name','LIKE',"%$name%")->where('state', $state )->orderBy('state', 'asc')->paginate();
													// 	}
													// 	return view('client.events.show',compact('screens','events','screensCount'));
													// }else{
													// 	// Flash::error('Ingrese un valor para generar la busqueda.');
													// 	// 	return redirect(url()->previous());
													// }
	}
	// public function changeUp($id , Request $request)
	// {
	// 	//llamado objeto 1
	// 	$obj1 = VersionPlaylistDetail::find($id);
	// 	//llamado id objeto 2
	// 	$callId = VersionPlaylistDetail::all()->where('version_playlist_id',$obj1->version_playlist_id)->where('orderContent',$request->order-1);
	// 	foreach($callId as $obj){
	// 	}
	// 	$id2 = $obj->id;
	// 	//llamado objeto 2
	// 	$obj2 = VersionPlaylistDetail::find($id2);
	// 	//intercambio de orden
	// 	$obj1->orderContent = $request->order-1;
	// 	$obj2->orderContent = $request->order;
	// 	if (empty($request)) {
	// 		Flash::error('Error');
	// 		return redirect(url()->previous());
	// 	}
	// 	//guardar cambios
	// 	$obj1->save();
	// 	$obj2->save();
	// 	return redirect(url()->previous($obj1));
	// }
	// public function changeDown($id , Request $request)
	// {
	// 	//llamado objeto 1
	// 	$obj1 = VersionPlaylistDetail::find($id);
	// 	//llamado id objeto 2
	// 	$callId = VersionPlaylistDetail::all()->where('version_playlist_id',$obj1->version_playlist_id)->where('orderContent',$request->order+1);
	// 	foreach($callId as $obj){
	// 	}
	// 	$id2 = $obj->id;
	// 	//llamado objeto 2
	// 	$obj2 = VersionPlaylistDetail::find($id2);
	// 	//intercambio de orden
	// 	$obj1->orderContent = $request->order+1;
	// 	$obj2->orderContent = $request->order;
	// 	if (empty($request)) {
	// 		Flash::error('Error');
	// 		return redirect(url()->previous());
	// 	}
	// 	//guardar cambios
	// 	$obj1->save();
	// 	$obj2->save();
	// 	return redirect(url()->previous($obj1));
	// }
	// public function changeJump(Request $request)
	// {
	// 	//extraer id de objeto inicial
	// 	$id = $request->id;
	// 	//si la nueva posicion viene vacia
	// 	if ($request->neworder == null) {
	// 		Flash::error('Debe ingresar una nueva posicion.');
	// 		return redirect(url()->previous());
	// 	}
	// 	//si la nueva posicion y la posicion actual son iguales
	// 	if ($request->neworder == $request->order) {
	// 		Flash::error('La nueva posicion no puede ser igual a la actual.');
	// 		return redirect(url()->previous());
	// 	}
	// 	//llamado de objeto inicial
	// 	$objIni = VersionPlaylistDetail::find($id);
	// 	//si la nueva posicion excede el rango de elementos
	// 	$countObjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id);
	// 	if ($countObjs->count() < $request->neworder) {
	// 		Flash::error('La nueva posicion no puede ser mayor a la cantidad total de elementos.');
	// 		return redirect(url()->previous());
	// 	}
	// 	//si la nueva posicion es menor de 1
	// 	if ($request->neworder < 1) {
	// 		Flash::error('La nueva posicion no puede ser menor que el primer elemento.');
	// 		return redirect(url()->previous());
	// 	}
	// 	//llamado coleccion de objs intermedios
	// 	if($request->order < $request->neworder){
	// 		$listobjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id)
	// 		->where('orderContent','<=',$request->neworder)->where('orderContent','>',$request->order);
	// 	}else if($request->order > $request->neworder){
	// 		$listobjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id)
	// 		->where('orderContent','>=',$request->neworder)->where('orderContent','<',$request->order);
	// 	}
	// 	//intercambio de orden y guardado
	// 	$order = $request->order;
	// 	$neworder = $request->neworder;
	// 	$objIni->orderContent = $neworder;
	// 	foreach($listobjs as $objs){
	// 		if($order < $neworder){
	// 			$objs->orderContent = $order;
	// 			$order = $order+1;
	// 		}else if($order > $neworder){
	// 			$neworder = $neworder+1;
	// 			$objs->orderContent = $neworder;
	// 		}
	// 		$objs->save();
	// 	}
	// 	$objIni->save();
	// 	Flash::success('Cambio de orden realizado.');
	// 	return redirect(url()->previous($objIni,$listobjs));
	// }
	// public function clone(Request $request)
	// {
	// 	$countObjs = VersionPlaylistDetail::all()->where('version_playlist_id',$request->version_playlist_id);
	// 	$element = VersionPlaylistDetail::create([
	// 		'version_playlist_id' => $request->version_playlist_id,
	// 		'content_id' => $request->content_id,
	// 		'orderContent' => $countObjs->count()+1,
	// 	]);
	// 	Flash::success('Se ha clonado el elemento correctamente.');
	// 	return redirect(url()->previous());

	// }
}
