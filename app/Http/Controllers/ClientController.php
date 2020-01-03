<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Screen;
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

}
