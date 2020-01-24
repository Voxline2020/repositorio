<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Event;
use App\Models\Company;
use App\Models\Screen;
use App\Models\Store;
use App\Repositories\ContentRepository;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Auth;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;
use Response;

class EventAssignationsController extends Controller
{
  /** @var  EventRepository */
  private $eventRepository;

  public function __construct()
  {
		$this->middleware('auth');
  }

  /**
   * Display a listing of the Event.
   *
   * @param Request $request
   * @return Response
   */

	// Format to AA::BB:CC
	public function formatDuration($duration){

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


