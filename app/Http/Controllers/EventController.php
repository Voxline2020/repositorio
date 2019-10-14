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

use App\Models\Store;

use App\Models\VersionPlaylistDetail;
use App\Models\ScreenPlaylistAsignation;
use App\Models\Screen;
use App\Models\Computer;


class EventController extends Controller
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
		$listsStore = Store::all();
		return view('events.index', compact('lists', 'listsStore'))
			->with('events', $events);
	}

	/**
	 * Show the form for creating a new Event.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('events.create');
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
			return redirect(route('events.index'));
		} else {
			Flash::error('La fecha  de termino tiene que ser mayor a la fecha de inicio ');
			return redirect(route('events.create'));
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

		return view('events.index', compact('event, lists'));
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

			return redirect(route('events.index'));
		}

		return view('events.edit')->with('event', $event);
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

			return redirect(route('events.index'));
		}
		$event = $this->eventRepository->update($request->all(), $id);
		Flash::success('Evento editado exitosamente');
		return redirect(route('events.index'));
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

			return redirect(route('events.index'));
		}
		$this->eventRepository->delete($id);
		Flash::success('El evento ha sido borrado.');
		return redirect(route('events.index'));
	}

	public function indexAssignContent(Request $request, $id)
	{
		$event = Event::where('id', $id)->first();
		$contents = $this->contentRepository->all();

		return view('events.indexAssignContent', compact('event'))
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


	public function ScreenShow($id)
	{
		$i = 0;
		$version_playlist_details = null;
		$screen_playlist_asignations = null;
		$event = Event::where('id', $id)->first();
		$screens2 = array();
		$contents = Content::where('event_id', $id)->paginate();
		foreach ($contents as $content) {
			$version_playlist_details =  VersionPlaylistDetail::where('content_id', $content->id)->get();
			if ($version_playlist_details != null) {
				foreach ($version_playlist_details as $version_playlist_detail) {
					$screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('version_id', $version_playlist_detail->version_id)->get();
					if ($screen_playlist_asignations != null) {
						foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
							$screens = Screen::where('id', $screen_playlist_asignation->screen_id)->first();

							if ($screens != null) {
								$screens2[$i] = $screens;
								$i++;
							}
						}
					}
				}
			}
		}
		return view('events.ScreenShow', compact('screens2', 'event'));
	}

	//filtros.
	public function filter_by_name(Request $request)
	{
		$eventsFinal = null;
		$filter = $request->get('nameFiltrar');
		$filterState = $request->get('state');
		$filterDate = $request->get('initdate');
		$filterDateEnd = $request->get('enddate');
		$filterSector = $request->get('sector');
		$filterFloor = $request->get('floor');
		$filterType = $request->get('type');
		if ($filter != null) {
			$events = Event::where('name', 'LIKE', "%$filter%")->paginate();
		} else {
			if ($filterState != null) {
				$events = Event::where('state', $filterState)->paginate();
			} else {
				if ($filterDate != null) {
					$events = Event::where('initdate', $filterDate)->paginate();
				} else {
					if ($filterDateEnd != null) {
						$events = Event::where('enddate', $filterDateEnd)->paginate();
					} else {
						$filter = $request->get('nameFiltrar');
						$filterSector = $request->get('sector');
						$filterFloor = $request->get('floor');
						$filterType = $request->get('type');
						$filterState = $request->get('state');
						$filterStore = $request->get('store');
						$i = 0;
						$a = 0;
						$version_playlist_details = null;
						$screen_playlist_asignations = null;
						$events = Event::all();
						foreach ($events as $event) {
							$screens2 = array();
							$contents = Content::where('event_id', $event->id)->paginate();
							foreach ($contents as $content) {
								$version_playlist_details =  VersionPlaylistDetail::where('content_id', $content->id)->get();
								if ($version_playlist_details != null) {
									foreach ($version_playlist_details as $version_playlist_detail) {
										$screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('version_id', $version_playlist_detail->version_id)->get();
										if ($screen_playlist_asignations != null) {
											foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
												if ($filterSector != null) {
													$screens = Screen::where([
														['id', $screen_playlist_asignation->screen_id],
														['sector', $filterSector]
													])->first();
													if ($screens != null) {
														$eventsFinal[$i] = $event;
														$i++;
													}
												} else {
													if ($filterFloor != null) {
														$screens = Screen::where([
															['id', $screen_playlist_asignation->screen_id],
															['floor', $filterFloor]
														])->first();
														if ($screens != null) {
															$eventsFinal[$i] = $event;
															$i++;
														}
													} else {
														if ($filterType != null) {
															$screens = Screen::where([
																['id', $screen_playlist_asignation->screen_id],
																['type', $filterType]
															])->first();
															if ($screens != null) {
																$eventsFinal[$i] = $event;
																$i++;
															}
														} else {
															if ($filterStore != null) {
																$screens = Screen::where([
																	['id', $screen_playlist_asignation->screen_id]
																])->first();
																if ($screens != null) {
																	$computer = Computer::where('id', $screens->computer_id)->first();
																	if ($computer != null) {
																		$store = Store::where([
																			['id', $computer->store_id],
																			['id',$filterStore]
																		])->first();
																		if ($store != null) {
																			$eventsFinal[$i] = $event;
																			$i++;
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$lists = Event::all();
			$listsStore = Store::all();
			if ($eventsFinal != null) {
				return view(
					'events.index',
					['events' => $eventsFinal],
					compact('lists', 'listsStore')
				);
			} else {
				if ($events != null) {
					if ($filter != null) {
						return view(
							'events.index',
							['events' => $events],
							compact('lists', 'listsStore')
						);
					} else {
						return view(
							'events.index',
							['events' => $events],
							compact('lists', 'listsStore')
						);
					}
				}
			}
		}
	}


	public function filterScreen_by_name(Request $request, $id)
	{
		$filter = $request->get('nameFiltrar');
		$filterSector = $request->get('sector');
		$filterFloor = $request->get('floor');
		$filterType = $request->get('type');
		$filterState = $request->get('state');
		$i = 0;
		$version_playlist_details = null;
		$screen_playlist_asignations = null;
		$event = Event::where('id', $id)->first();
		$screens2 = array();
		$contents = Content::where('event_id', $id)->paginate();
		foreach ($contents as $content) {
			$version_playlist_details =  VersionPlaylistDetail::where('content_id', $content->id)->get();
			if ($version_playlist_details != null) {
				foreach ($version_playlist_details as $version_playlist_detail) {
					$screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('version_id', $version_playlist_detail->version_id)->get();
					if ($screen_playlist_asignations != null) {
						foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
							if ($filter != null) {
								$screens = Screen::where([
									['id', $screen_playlist_asignation->screen_id],
									['name', 'LIKE', "%$filter%"]
								])->first();
							} else {
								if ($filterSector != null) {
									$screens = Screen::where([
										['id', $screen_playlist_asignation->screen_id],
										['sector', $filterSector]
									])->first();
								} else {
									if ($filterFloor != null) {
										$screens = Screen::where([
											['id', $screen_playlist_asignation->screen_id],
											['floor', $filterFloor]
										])->first();
									} else {
										if ($filterType != null) {
											$screens = Screen::where([
												['id', $screen_playlist_asignation->screen_id],
												['type', $filterType]
											])->first();
										} else {
											if ($filterState != null) {
												$screens = Screen::where([
													['id', $screen_playlist_asignation->screen_id],
													['state', $filterState]
												])->first();
											} else {
												$screens = Screen::where([
													['id', $screen_playlist_asignation->screen_id]
												])->first();
											}
										}
									}
								}
							}
							if ($screens != null) {
								$screens2[$i] = $screens;
								$i++;
							}
						}
					}
				}
			}
		}
		return view('events.ScreenShow', compact('screens2', 'event'));
	}
}
