<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Repositories\EventRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Event;
use App\Models\Content;
use App\Repositories\ContentRepository;

class EventControllerOld extends Controller
{
	/** @var  EventRepository */
	private $eventRepository;

	public function __construct(EventRepository $eventRepo, ContentRepository $contentRepo)
	{
		$this->eventRepository = $eventRepo;
		$this->contentRepository = $contentRepo;
	}

	/**
	* Display a listing of the Event.
	*
	* @param Request $request
	* @return Response
	*/
	public function index(Request $request)
	{
		$events = $this->eventRepository->all();
		$lists = Event::all();
		return view('eventsOld.index', compact('lists'))
			->with('events', $events);
	}

	/**
	* Show the form for creating a new Event.
	*
	* @return Response
	*/
	public function create()
	{
		return view('eventsOld.create');
	}

	/**
	* Store a newly created Event in storage.
	*
	* @param CreateEventRequest $request
	*
	* @return Response
	*/
	public function store(CreateEventRequest $request)
	{


		if ($request->initdate <= $request->enddate) {
			$input = $request->all();
			$event = $this->eventRepository->create($input);
			Flash::success('Evento agregado exitosamente');
			return redirect(route('eventsOld.index'));
		} else {
			Flash::error('La fecha  de termino tiene que ser mayor a la fecha de inicio ');
			return redirect(route('eventsOld.create'));
		}
	}

	/**
	* Display the specified Event.
	*
	* @param  int $id
	*
	* @return Response
	*/
	public function show($id)
	{
		$event = Event::where('id', $id)->paginate();
		$eventName = Event::where('id', $id)->first();
		$content = Content::where('event_id', $id)->paginate();
		$lists = Event::all();

		if (empty($event)) {
			Flash::error('Evento no encontrado');

			return redirect(route('contents.index'));
		}

		return view('eventsOld.index', compact('event, lists'));
	}

	/**
	* Show the form for editing the specified Event.
	*
	* @param  int $id
	*
	* @return Response
	*/
	public function edit($id)
	{
		$event = $this->eventRepository->find($id);

		if (empty($event)) {
			Flash::error('Evento no encontrado');

			return redirect(route('eventsOld.index'));
		}

		return view('eventsOld.edit')->with('event', $event);
	}

	/**
	* Update the specified Event in storage.
	*
	* @param  int              $id
	* @param UpdateEventRequest $request
	*
	* @return Response
	*/
	public function update($id, UpdateEventRequest $request)
	{
		$event = $this->eventRepository->find($id);

		if (empty($event)) {
			Flash::error('Evento no encontrado');

			return redirect(route('eventsOld.index'));
		}
		$event = $this->eventRepository->update($request->all(), $id);
		Flash::success('Evento editado exitosamente');
		return redirect(route('eventsOld.index'));
	}

	/**
	* Remove the specified Event from storage.
	*
	* @param  int $id
	*
	* @return Response
	*/
	public function destroy($id)
	{
		$event = $this->eventRepository->find($id);

		if (empty($event)) {
			Flash::error('Event not found');

			return redirect(route('eventsOld.index'));
		}
		$this->eventRepository->delete($id);
		Flash::success('El evento ha sido borrado.');
		return redirect(route('eventsOld.index'));
	}

	public function indexAssignContent(Request $request, $id)
	{
		$event = Event::where('id', $id)->first();
		$contents = $this->contentRepository->all();

		return view('eventsOld.indexAssignContent', compact('event'))
			->with('contents', $contents);
	}
	public function Assign($eventId, $id)
	{
		$content = Content::find($id);
		$content->event_id = $eventId;
		$ok = $content->save();
		Flash::success('Content updated successfully.');
		return redirect(route('contents.index'));
	}

	//filtros.
	public function filter_by_name(Request $request)
	{
		$filter = $request->get('nameFiltrar');
		$filterState = $request->get('state');
		$filterDate= $request->get('initdate');
		if($filter!=null){
			$events = Event::where('name', 'LIKE', "%$filter%")->paginate();
		}else{
			if($filterState!=null){
				$events = Event::where('state', $filterState)->paginate();
			}else{
				if($filterDate!=null){
					$events = Event::where('initdate', $filterDate)->paginate();
				}
				else{
					$events= Event::all();
				}


			}
		}
		$lists = Event::all();
		return view(
			'eventsOld.index',
			['events' => $events],
			compact('lists')
		);
	}
}
