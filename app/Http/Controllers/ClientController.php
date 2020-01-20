<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Screen;
use App\Models\Playlist;
use App\Models\VersionPlaylistDetail;
use App\Models\Content;
use App\Models\Company;
use App\Models\Computer;
use App\Models\Store;
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
		if (Auth::user()->hasRole('Administrador')){
			$events = $this->eventRepository->all();
			$screens = Screen::with(['computer','computer.store'])->orderBy('state', 'asc')->paginate();
			$screensCount = Screen::with(['computer','computer.store'])->get();
		}else{
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
		}
		$error = $request->session()->get('error');
		if(!empty($error)){
			Flash::error($error);
		}
		return view('client.index',compact('screens','events','screensCount'));
	}
	public function show($id)
	{
		//filtramos la pantalla que queremoos ver con el id
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
		//ahora buscamos la playlist asignada a esa pantalla
		$playlist = Playlist::wherehas('versionPlaylists', function ($query) {
            $query->whereHas('versionPlaylistDetails', function ($query) {
                $query->whereHas('content', function ($query) {
                });
            });
		})->find($screen->playlist_id);
		//ahora hacemos una lista de los contenidos de esa playlist
		$list = [];
        foreach($playlist->versionPlaylists AS $version){
		}
		foreach($version->versionPlaylistDetails AS $detail){
			array_push($list,$detail->id);
		}
		//aca traemos toda la info de los contenidos extraidos en la lista.
		$details = VersionPlaylistDetail::with(['content'])->orderBy('orderContent','ASC')->find($list);

		return view('client.screen.show')->with('screen',$screen)->with('playlist', $playlist)->with('details',$details);
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
	public function changeUp($id , Request $request)
	{
		//llamado objeto 1
		$obj1 = VersionPlaylistDetail::find($id);
		//llamado id objeto 2
		$callId = VersionPlaylistDetail::all()->where('version_playlist_id',$obj1->version_playlist_id)->where('orderContent',$request->order-1);
		foreach($callId as $obj){
		}
		$id2 = $obj->id;
		//llamado objeto 2
		$obj2 = VersionPlaylistDetail::find($id2);
		//intercambio de orden
		$obj1->orderContent = $request->order-1;
		$obj2->orderContent = $request->order;
		if (empty($request)) {
			Flash::error('Error');
			return redirect(url()->previous());
		}
		//guardar cambios
		$obj1->save();
		$obj2->save();
		return redirect(url()->previous($obj1));
	}
	public function changeDown($id , Request $request)
	{
		//llamado objeto 1
		$obj1 = VersionPlaylistDetail::find($id);
		//llamado id objeto 2
		$callId = VersionPlaylistDetail::all()->where('version_playlist_id',$obj1->version_playlist_id)->where('orderContent',$request->order+1);
		foreach($callId as $obj){
		}
		$id2 = $obj->id;
		//llamado objeto 2
		$obj2 = VersionPlaylistDetail::find($id2);
		//intercambio de orden
		$obj1->orderContent = $request->order+1;
		$obj2->orderContent = $request->order;
		if (empty($request)) {
			Flash::error('Error');
			return redirect(url()->previous());
		}
		//guardar cambios
		$obj1->save();
		$obj2->save();
		return redirect(url()->previous($obj1));
	}
	public function changeJump(Request $request)
	{
		//extraer id de objeto inicial
		$id = $request->id;
		//si la nueva posicion viene vacia
		if ($request->neworder == null) {
			Flash::error('Debe ingresar una nueva posicion.');
			return redirect(url()->previous());
		}
		//si la nueva posicion y la posicion actual son iguales
		if ($request->neworder == $request->order) {
			Flash::error('La nueva posicion no puede ser igual a la actual.');
			return redirect(url()->previous());
		}
		//llamado de objeto inicial
		$objIni = VersionPlaylistDetail::find($id);
		//si la nueva posicion excede el rango de elementos
		$countObjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id);
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
		if($request->order < $request->neworder){
			$listobjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id)
			->where('orderContent','<=',$request->neworder)->where('orderContent','>',$request->order);
		}else if($request->order > $request->neworder){
			$listobjs = VersionPlaylistDetail::all()->where('version_playlist_id',$objIni->version_playlist_id)
			->where('orderContent','>=',$request->neworder)->where('orderContent','<',$request->order);
		}
		//intercambio de orden y guardado
		$order = $request->order;
		$neworder = $request->neworder;
		$objIni->orderContent = $neworder;
		foreach($listobjs as $objs){
			if($order < $neworder){
				$objs->orderContent = $order;
				$order = $order+1;
			}else if($order > $neworder){
				$neworder = $neworder+1;
				$objs->orderContent = $neworder;
			}
			$objs->save();
		}
		$objIni->save();
		Flash::success('Cambio de orden realizado.');
		return redirect(url()->previous($objIni,$listobjs));
	}
	public function clone(Request $request)
	{
		$countObjs = VersionPlaylistDetail::all()->where('version_playlist_id',$request->version_playlist_id);
		$element = VersionPlaylistDetail::create([
			'version_playlist_id' => $request->version_playlist_id,
			'content_id' => $request->content_id,
			'orderContent' => $countObjs->count()+1,
		]);
		Flash::success('Se ha clonado el elemento correctamente.');
		return redirect(url()->previous());

	}
}
