<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use App\Models\Screen;
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
		$user = Auth::user()->name;
		$events = $this->eventRepository->all();
		$screens= Screen::all();
		$modelComputer = Computer::all();
		$modelStore = Store::all();
		$screenActive = Screen::where('state', '1')->count();
		$screenInactive= Screen::where('state', '0')->count();
		return view('client.index',compact('screenActive' ,'screenInactive','user','screens','events','modelComputer','modelStore'));
	}
}
