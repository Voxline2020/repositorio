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
		$this->eventRepository = $eventRepo;
	}
	//mostrar compañias
	public function index(Request $request)
	{
		$events = $this->eventRepository->all()->where('company_id', Auth::user()->company_id);
		$screens = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->orderBy('state', 'asc')->paginate();
		$screensCount = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->get();
		return view('client.index',compact('screens','events','screensCount'));
	}
	public function show($id)
	{
		//filtramos la pantalla que queremoos ver con el id
		$screen = Screen::whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});
		})->find($id);
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
		$details = VersionPlaylistDetail::orderBy('orderContent','ASC')->find($list);
																														
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
	public function changePosition(Request $request)
	{

	}
}
