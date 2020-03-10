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
		$events = Event::where('company_id', Auth::user()->company_id);
		$stores = Store::where('company_id', Auth::user()->company_id)->get();
		$dateNow = \Carbon\Carbon::now()->format('Y-m-d\TH:i:s');
		$eventsActive = $events->where('state',1)->where('enddate','>',$dateNow)->get();
		$eventsInactive = $events->where('state',0)->where('initdate','>',$dateNow)->get();
		$screens = Screen::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'ASC')->paginate();
		$screensCount = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$error = $request->session()->get('error');
		if(!empty($error)){
			Flash::error($error);
		}
		return view('client.index',compact('screens','events','screensCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive)
		->with('stores',$stores);
	}
	public function show($id)
	{
		//fijamos el hoy
		$today=date('Y-m-d H:i:s');
		//filtramos la pantalla que queremos ver con el id
		$screen = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->find($id);
		//ahora buscamos los eventos compatibles con la pantalla
		$contents = Content::whereHas('event', function ($query) use ($today) {
			$query->where('company_id', Auth::user()->company_id)->where('enddate','>=',$today);
		})->where('width',$screen->width)->where('height',$screen->height)->get();
		$list=[];
		foreach($contents as $content){
			array_push($list,$content->event->id);
		}
		//extraemos los eventos compatibles
		$events= Event::find($list);
		//eventos asignados activos
		$eventAssigns = EventAssignation::whereHas('content', function ($query) use ($today) {
			$query->whereHas('event', function ($query) use ($today){
				 $query->where('enddate','>=',$today);
			});
		})->where('screen_id',$id)->where('state',1)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//eventos asignados inactivos
		$eventInactives = EventAssignation::whereHas('content', function ($query) use ($today) {
			$query->whereHas('event', function ($query) use ($today) {
				$query->where('enddate','>=',$today);
			});
		})->where('screen_id',$id)->where('state',0)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//duracion total del contenido asignado
		$horas=[];
		foreach($eventAssigns as $assign){
			array_push($horas,$assign->content->duration);
		}
		$total = 0;
		foreach($horas as $h) {
				$parts = explode(":", $h);
				$total += $parts[2] + $parts[1]*60 + $parts[0]*3600;
		}
		$totalduration = Carbon::parse($total)->format("H:i:s");
		return view('client.screen.show')
		->with('screen',$screen)
		->with('events', $events)
		->with('eventAssigns', $eventAssigns)
		->with('totalduration',$totalduration)
		->with('eventInactives', $eventInactives);
	}
	public function filter_by_name(Request $request)
	{
		$name = $request->nameFiltrar;
		$state = $request->state;
		$store = $request->store;
		$stores = Store::where('company_id', Auth::user()->company_id)->get();
		$events = $this->eventRepository->all()->where('company_id', Auth::user()->company_id);
		$dateNow = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
		$eventsActive = $events->where('state',1);
		$eventsInactive = $events->where('state',0)->where('initdate','>',$dateNow);
		$screensCount = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$screens = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		});
		if($name==null && $state==null && $store==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		if($name != null){
			$screens->where('name','LIKE',"%$name%")->orderBy('state', 'asc');
		}
		if($state != null){
			$screens->where('state', $state )->orderBy('state', 'asc');
		}
		if($store != null){
			$screens = Screen::whereHas('computer', function ($query) use($store) {
			$query->whereHas('store', function ($query) use($store) {
				$query->where('company_id', Auth::user()->company_id);
			})->where('store_id', $store );
		})->orderBy('state', 'asc');
		}
		$screens=$screens->paginate();
		if(count($screens)==0){
			Flash::info('No se encontro ningun resultado.');
			return redirect(url()->previous());
		}
		return view('client.index',compact('screens','screensCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive)
		->with('screens',$screens)
		->with('stores',$stores);
	}
	public function filter_active(Request $request)
	{
		if($request->nameFiltrar==null&&$request->initdate==null&&$request->enddate==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		$dateNow = Carbon::now()->format('Y-m-d H:i');
		$initdate = Carbon::parse(str_replace('/', '-',$request->initdate))->format('Y-m-d H:i');
		$enddate = Carbon::parse(str_replace('/', '-',$request->enddate))->format('Y-m-d H:i');
		$eventsActive = Event::where('state',1);
		$eventsInactive = Event::where('state',0)->where('initdate','>',$dateNow);
		if($eventsActive->count()==0){
			Flash::error('No se puede realizar la busqueda ya que no existen elementos para buscar.');
			return redirect(url()->previous());
		}
		if($request->nameFiltrar!=null){
			$eventsActive->Where('name','like',"%$request->nameFiltrar%");
		}
		if($request->initdate!=null){
			$eventsActive->where('initdate','>=',$initdate);
		}
		if($request->enddate!=null){
			$eventsActive->where('enddate','<=',$enddate);
		}
		$eventsActive = $eventsActive->get();
		if($eventsActive->count()==0){
			Flash::info('No se encontro ningun resultado.');
		}
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
		$stores = Store::where('company_id', Auth::user()->company_id)->get();
		return view('client.index',compact('screens','screensCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive)
		->with('stores',$stores);
	}
	public function filter_inactive(Request $request)
	{
		if($request->nameFiltrar==null&&$request->initdate==null&&$request->enddate==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		$dateNow = Carbon::now()->format('Y-m-d H:i');
		$initdate = Carbon::parse(str_replace('/', '-',$request->initdate))->format('Y-m-d H:i');
		$enddate = Carbon::parse(str_replace('/', '-',$request->enddate))->format('Y-m-d H:i');
		$eventsActive = Event::where('state',1);
		$eventsInactive = Event::where('state',0)->where('initdate','>',$dateNow);
		if($eventsInactive->count()==0){
			Flash::error('No se puede realizar la busqueda ya que no existen elementos para buscar.');
			return redirect(url()->previous());
		}
		if($request->nameFiltrar!=null){
			$eventsActive->Where('name','like',"%$request->nameFiltrar%");
		}
		if($request->initdate!=null){
			$eventsActive->where('initdate','>=',$initdate);
		}
		if($request->enddate!=null){
			$eventsActive->where('enddate','<=',$enddate);
		}
		$eventsActive = $eventsActive->get();
		if($eventsActive->count()==0){
			Flash::info('No se encontro ningun resultado.');
		}
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
		return view('client.index',compact('screens','screensCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive);
	}
	//Events///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function indexEvent(Request $request)
  {
		$now = Carbon::now()->format('Y/m/d H:i');
		$old_check = 0;
    $company = Company::where('id',  Auth::user()->company_id)->first();
		$events = Event::with('contents')
		->where('company_id',  Auth::user()->company_id)
		->where('enddate','>=',$now)
		->orderBy('state', 'asc')
		->paginate();
    return view('client.events.index', compact('events'))->with('company', $company)->with('old_check', $old_check);

	}
	public function showEvent(Event $event)
  {
		//comprobamos que el evento tenga contenido
		if($event->contents->count()!=0){
			//obtenemos los contenidos del evento y vemos su asignacion
			$assignsList = [];
			foreach ($event->contents AS $content) {
				$assign = EventAssignation::where('content_id',$content->id)->first();
				if($assign!=null){
					array_push($assignsList, $assign->id);
				}
			};
			//traemos las asignaciones de eventos que coincidan con los contenidos del evento que estamos revisando
			$eventAssigns = EventAssignation::find($assignsList);
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
  }
  public function storeEvent(Company $company, Request $request)
  {
		$name = Event::where('company_id',$request->company_id)->where('name',$request->name)->get();
		if($name->count() != 0){
			Flash::error('El evento "'.$request->name.'" ya existe.');
			return redirect(route('clients.events.create'));
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
			return redirect(route('clients.events.create'));
		}else{
			$input = $request->all();
			Event::create($input);
			$id = [];
			$callevent =  Event::where('company_id',$request->company_id)->where('name',$request->name)->get();
			// foreach($callevent as $e){
			// 	array_push($id,$e->id);
			// }
			$event = Event::find($callevent[0]->id);
			Flash::success('Evento agregado exitosamente.');
			return redirect(route('clients.events.edit',['event'=>$event]));
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

		foreach ($event->contents as $content) {
			foreach ($content->eventAssignations as $eventAssignation) {
				if($event->state == 1){
					$eventAssignation->screen->version = $eventAssignation->screen->version +1;
					$eventAssignation->screen->save();
				}
				$eventAssignation->delete();
			}
			$content->delete();
		}
    $event->delete();
    Flash::success('Evento borrado.');
    return redirect(route('clients.events.index'));
	}
	public function filterEvent_by(Request $request)
  {
		if($request->nameFiltrar==null&&$request->state==null&&$request->initdate==null&&$request->enddate==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		$old_check = $request->old_check;
		$now = Carbon::now()->format('Y/m/d H:i');
		$initdate = Carbon::parse(str_replace('/', '-',$request->initdate))->format('Y-m-d H:i');
		$enddate = Carbon::parse(str_replace('/', '-',$request->enddate))->format('Y-m-d H:i');
		if($old_check==0){
			$events = Event::with('contents')->where('enddate','>=',$now)->where('company_id', Auth::user()->company_id);
		}else{
			$events = Event::with('contents')->where('company_id', Auth::user()->company_id);
		}

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
		}
		return view('client.events.index', compact('events'))->with('old_check',$old_check);
	}
	public function view_old(Request $request)
	{
		$old_check = 1;
    $company = Company::where('id',  Auth::user()->company_id)->first();
		$events = Event::where('company_id',  Auth::user()->company_id)
		->orderBy('state', 'asc')
		->paginate();
    return view('client.events.index', compact('events'))->with('company', $company)->with('old_check', $old_check);
	}
	public function destroyEventAssign(EventAssignation $assign)
  {
    $assign->delete();
		Flash::success('Evento desasignado.');
		$screen = Screen::find($assign->screen_id);
		$screen->version = $screen->version+1;
		$screen->save();
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
