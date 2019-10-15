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

		$events = $this->eventRepository->all()->where('company_id', Auth::user()->company_id);
		$screens= Screen::with('computer','computer.store')->orderBy('state', 'asc')->paginate();
		$screensCount = Screen::with('computer','computer.store')->orderBy('state', 'asc')->get();
		return view('client.index',compact('screens','events','screensCount'));
	}
}
