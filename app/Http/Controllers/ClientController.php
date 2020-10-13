<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str as Str;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Content;
use App\Models\Company;
use App\Models\Computer;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\Store;
use Carbon\Carbon;
use Flash;
use Response;

use Illuminate\Support\Facades\Log;

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
		Log::debug('En el index.');
		$events = Event::where('company_id', Auth::user()->company_id);
		$stores = Store::where('company_id', Auth::user()->company_id)->get();
		$eventsmenu = $events->get();
		$dateNow = \Carbon\Carbon::now()->format('Y-m-d\TH:i:s');
		$eventsActive = $events->where('state',1)->where('enddate','>',$dateNow)->get();
		$eventsInactive = $events->where('state',0)->where('initdate','>',$dateNow)->get();
		$devices = Device::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'DESC')->paginate();
		$devicesCount = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$error = $request->session()->get('error');
		if(!empty($error)){
			Flash::error($error);
		}
		return view('client.index',compact('devices','events','devicesCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive)
		->with('stores',$stores)
		->with('eventsmenu', $eventsmenu);
	}
	public function show($id)
	{
		//fijamos el hoy
		$today=date('Y-m-d H:i:s');
		//filtramos la pantalla que queremos ver con el id
		$device = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->find($id);
		//ahora buscamos los eventos compatibles con la pantalla
		$contents = Content::whereHas('event', function ($query) use ($today) {
			$query->where('company_id', Auth::user()->company_id)->where('enddate','>=',$today);
		})->where('width',$device->width)->where('height',$device->height)->get();
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
		})->where('device_id',$id)->where('state',1)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
		//eventos asignados inactivos
		$eventInactives = EventAssignation::whereHas('content', function ($query) use ($today) {
			$query->whereHas('event', function ($query) use ($today) {
				$query->where('enddate','>=',$today);
			});
		})->where('device_id',$id)->where('state',0)->orderBy('order','ASC')->orderBy('content_id','ASC')->paginate();
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
		return view('client.device.show')
		->with('device',$device)
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
		$devicesCount = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$devices = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		});
		if($name==null && $state==null && $store==null){
			Flash::error('Debe ingresar almenos un filtro para la busqueda.');
			return redirect(url()->previous());
		}
		if($name != null){
			$devices->where('name','LIKE',"%$name%")->orderBy('state', 'asc');
		}
		if($state != null){
			$devices->where('state', $state )->orderBy('state', 'asc');
		}
		if($store != null){
			$devices = Device::whereHas('computer', function ($query) use($store) {
			$query->whereHas('store', function ($query) use($store) {
				$query->where('company_id', Auth::user()->company_id);
			})->where('store_id', $store );
		})->orderBy('state', 'asc');
		}
		$devices=$devices->paginate();
		if(count($devices)==0){
			Flash::info('No se encontro ningun resultado.');
			return redirect(url()->previous());
		}
		return view('client.index',compact('devices','devicesCount'))
		->with('eventsActive',$eventsActive)
		->with('eventsInactive',$eventsInactive)
		->with('devices',$devices)
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
		$devices = Device::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'asc')->paginate();
		$devicesCount = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		$stores = Store::where('company_id', Auth::user()->company_id)->get();
		return view('client.index',compact('devices','devicesCount'))
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
		$devices = Device::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'asc')->paginate();
		$devicesCount = Device::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		return view('client.index',compact('devices','devicesCount'))
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
			foreach ($eventAssigns AS $assign) {
				array_push($list, $assign->device_id);
			};
			//extraemos las pantallas
			$devices= Device::find($list);
			$stores = Store::where('company_id',Auth::user()->company_id)->get();
			$types = DeviceType::all();
			return view('client.events.show', compact('event'))
			->with('stores',$stores)
			->with('types',$types)
			->with('devices',$devices);
		}else{
			$devices= $event->contents;
			$stores = Store::where('company_id',Auth::user()->company_id)->get();
			$types = DeviceType::all();
			return view('client.events.show', compact('event'))
			->with('stores',$stores)
			->with('types',$types)
			->with('devices',$devices);
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
					$eventAssignation->device->version = $eventAssignation->device->version +1;
					$eventAssignation->device->save();
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
		//$device = Device::find($assign->device_id);
		//$device->version = $device->version+1;
		//$device->save();
    return redirect()->route('clients.show', ['id'=>$assign->device_id]);
	}
	public function filter_device(Company $company,Request $request)
	{
		$company = Auth::user()->company_id;
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
					$query->where('company_id', $company);
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
		$stores = Store::where('company_id',$company)->get();
		$types = DeviceType::all();
		return view('client.events.show')
		->with('devices',$devices)
		->with('stores',$stores)
		->with('types',$types)
		->with('event',$event);
	}
	public function changeStatus(Request $request, $id)
	{
		if (empty($request)) {
			Flash::error('Error');
			return redirect(url()->previous());
		}
		$device = Device::find($id);
		$device->state = $request['state'];
		$device->save();
		Flash::success('Estado actualizado');
		return redirect(url()->previous($device));
	}
	public function eventAssign($id, Request $request)
	{
		$request->merge(['slug' => Str::slug($request['name'])]);
		$device = Device::find($id);
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
				//$device->version=$device->version+1;
				//$device->save();
				$input = $request->all();
				EventAssignation::create($input);
			}
			Flash::success('Evento "'.$event->name.'" asignado exitosamente');
		}
		return redirect(url()->previous($device));
	}
	public function changeOrder(Request $request)
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
		//traemos la pantalla
		$device = Device::find($request->device);
		//si la nueva posicion y la posicion actual son iguales
		if ($request->neworder == $objIni->order) {
			Flash::error('La nueva posicion no puede ser igual a la actual.');
			return redirect(url()->previous());
		}
		
		/* Gustavo Desactivar esta validacion 
		//si la nueva posicion excede el rango de elementos
		$countObjs = EventAssignation::where('device_id',$device->id)->get();
		if ($countObjs->count() < $request->neworder) {
			Flash::error('La nueva posicion no puede ser mayor a la cantidad total de elementos.');
			return redirect(url()->previous());
		}*/
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
		//+1 a la version de la playlist
		//$device->version = $device->version + 1;
		//$device->save();

		//Gustavo
		$ordereventlist = EventAssignation::join("devices" , 'event_assignations.device_id' , '=' , "devices.id")
							->where("devices.id" , "=",  $device->id)
							->select("event_assignations.id" , "event_assignations.order", "devices.id as deviceId")
							->orderBy('order', 'ASC')
							->get();
		$contadorOrden = 1;
		//dd($ordereventlist);
		foreach($ordereventlist as $event )
		{
			//$dbevent = EventAssignation::first();
			
			$event->order = $contadorOrden;
			$event->save();
			$contadorOrden =  $contadorOrden + 1 ;
		}
		Flash::success('Cambio de orden realizado.');
		return redirect(url()->previous());
	}
	public function cloneEvent(Request $request)
	{
		//asignamos el order para el clon
		$count_assigns = EventAssignation::where('device_id',$request->device_id)->where('state',1)->count()+1;
		$request->merge([
			"order"=>$count_assigns,
		]);
		// extraemos los datos del elemento original
		$input = $request->all();
		//cambiamos version en pantalla
		$device=Device::find($request->device_id);
		//$device->version = $device->version+1;
		//$device->save();
		//creamos el nuevo elemento clonado
		EventAssignation::create($input);
		Flash::success('Se ha clonado el elemento correctamente.');
		return redirect(url()->previous());

	}
}
