<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Event;
use App\Models\EventAssignation;
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

class EventController extends Controller
{
  /** @var  EventRepository */
  private $eventRepository;

  public function __construct(EventRepository $eventRepo, ContentRepository $contentRepo)
  {
		$this->middleware('auth');
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
    $company = Company::where('id',  auth()->user()->company_id)->first();
    // $events = $this->eventRepository->all();
    $events = Event::where('company_id',  auth()->user()->company_id)->orderBy('state', 'asc')->paginate();
    // $lists = Event::where('company_id', $id);
    $listsStore = Store::all();
    return view('events.index', compact('events', 'listsStore'))->with('company', $company);

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
      $request->merge(['slug' => Str::slug($request['name'])]);
      $event = $this->eventRepository->create($input);
      Flash::success('Evento agregado exitosamente.');
      return redirect(route('events.index'));
    } else {
      Flash::error('La fecha  de termino tiene que ser mayor a la fecha de inicio ');
      return redirect(route('events.create'));
    }
  }

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


  public function fileStore(Request $request)
  {
    $files = $request->file('file');

    $event = null;
    if (isset($request['event_id']) && !empty($request['event_id'])) {
      $event = Event::find($request['event_id']);
    } else {
      return response('Evento no existe o el id es incorrecto', 404)->header('Content-Type', 'text/plain');
		}
		if ($request->hasFile('file')) {
			//Rescata valores de los archivos subidos
			foreach ($files as $file) {
				//Analizar Video
				$getID3 = new \getID3;
				$fileX = $getID3->analyze($file);
				$filetype = $file->getClientOriginalExtension();
				$mime = $file->getClientMimeType();
				$user_id = Auth::user()->id;
				$size = $file->getSize();
				$width = $fileX['video']['resolution_x'];
				$height = $fileX['video']['resolution_y'];
				$duration = EventController::formatDuration($fileX['playtime_string']);

				//Nombre archivo
				$name = Str::slug($event->slug . '_' . $width . 'x' . $height);
				$original_name = Str::slug($event->slug . '_' . $width . 'x' . $height);
				$slug = Str::slug($name);

				//Guardar archivos
				$path = Storage::disk('videos')->put($event->slug . "/" . $name, $file);

				$request->merge([
					'user_id' => $user_id,
					'location' => $path,
					'original_name' => $original_name,
					'slug' => $slug,
					'filetype' => $filetype,
					'mime' => $mime,
					'event_id' => $event->id,
					'size' => $size,
					'width' => $width,
					'height' => $height,
					'name' => $name,
					'duration' => $duration,
				]);

				// $file->move($path, $original_name . '.mp4');

				$input = $request->all();
				// //validar si ya existe la resulcion y guardar
				$content = Content::where('name',$name)->get();
				$contentValidate = $content->count();
<<<<<<< HEAD
        //anular la validacion
        $contentValidate = 0;
=======
				 //anular 
				$contentValidate = 0;
>>>>>>> arreglo2
				if($contentValidate!=0){
					Flash::error('Esta resolucion ya esta asignada al evento.');
				}else{
					$this->contentRepository->create($input);
				}
			}
			return response('OK', 200)->header('Content-Type', 'text/plain');
		}
    return redirect()->route('events.index');
  }


  /**
   * Display the specified Event.
   *
   * @param  int $id
   *
   * @return Response
   */
  public function show(Event $event)
  {

    if (empty($event)) {
      Flash::error('Evento no encontrado');
      return redirect()->back();
    }

    return view('events.show', compact('event'));
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
		$request->merge([
				"initdate"=> Carbon::createFromFormat('d/m/Y H:i',$request["initdate"])->toDateTimeString(),
				"enddate"=> Carbon::createFromFormat('d/m/Y H:i',$request["enddate"])->toDateTimeString(),
				]);

    if (empty($event)) {
      Flash::error('Evento no encontrado');
      return redirect(route('events.index'));
		}
    $event = $this->eventRepository->update($request->all(), $id);
    Flash::success('Evento editado exitosamente');
		// return redirect(route('events.show',['id' => $id]));
		return redirect()->route('events.show', [$id]);
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
      Flash::error('Evento no encontrado');

      return redirect(route('companies.events.index',[$id]));
    }
    $this->eventRepository->delete($id);
		Flash::success('El evento ha sido borrado.');
		if (Auth::user()->hasRole('Administrador')){
			return redirect(route('companies.events.index',[$event->company_id]));
		}
		return redirect(route('clients.events.index',[$event->company_id]));
  }

  //ANCHOR Asignations

  public function indexAssign(Event $event, Content $content, Request $request)
  {

    $screens = Screen::with(['computer','computer.store'])->whereHas('computer', function ($query) {
			$query->whereHas('store', function ($query) {
				$query->where('company_id', Auth::user()->company_id);
			});

    })->where('width', $content->width)->where('height', $content->height)->orderBy('computer_id', 'ASC')->paginate();
    $screens->appends(request()->query());


    // dd($screens);
    return view('events.assignations.index', compact('event', 'content', 'screens'))->with('screensChbx', $request->screensChbx);
  }

  public function storeAssign(Event $event, Content $content, Request $request)
  {

		$screensId = $request->input('screensChbx');

    foreach ($screensId as $screenId) {
      $screen = Screen::find($screenId);
      //existe la pantalla?
      if (isset($screen) || !empty($screen)) {
        //tiene asignado una playlist?
        if (isset($screen->playlist_id) || !empty($screen->playlist_id)) {

        }
        //si no tiene se crea una nueva
        else {
          $plName = 'pl' . $screen->id . '-' . rand(0, 100);
          $newPlaylist = Playlist::create([
            'name' => $plName,
            'slug' => Str::slug($plName),
            'user_id' => Auth::user()->id,
          ]);

          $screen->playlist_id = $newPlaylist->id;
          $screen->save();
        }

        //obtener version activa de la pantalla
        $vPlaylistActive = $screen->playlist->versionPlaylists->where('state', 1)->first();

        //si existe se obtiene y se agrega contenido a esta
        if (isset($vPlaylistActive) || !empty($vPlaylistActive)) {
          //Crear nueva version del playlist
          //nuevo nombre con la version al final
          $versionNueva = $vPlaylistActive->version + 1;
          $vplName = 'vpl' . $screen->id . '-v' . $versionNueva;

          $newVPlaylist = VersionPlaylist::create([
            'name' => $vplName,
            'slug' => Str::slug($vplName),
            'version' => $versionNueva,
            'playlist_id' => $vPlaylistActive->playlist_id,
            'state' => 1,
          ]);

          //se agregan contenidos antiguos
          foreach ($vPlaylistActive->versionPlaylistDetails as $vPlaylistDetail) {
            $oldPlaylistDetail = VersionPlaylistDetail::create([
              'version_playlist_id' => $newVPlaylist->id,
              'content_id' => $vPlaylistDetail->content_id,
              'orderContent' => $vPlaylistDetail->orderContent,
            ]);
          }

          //se agrega contenido nuevo
          $newPlaylistDetail = VersionPlaylistDetail::create([
            'version_playlist_id' => $newVPlaylist->id,
            'content_id' => $content->id,
            'orderContent' => $vPlaylistDetail->orderContent+1,
          ]);

          //se desactiva la version anterior
          $vPlaylistActive->state = 0;
          $vPlaylistActive->save();
        }
        //si no existe una version activa se crea una
        else {
          $vplName = 'vpl' . $screen->id . '-v' . '1';
          $newVPlaylistActive = VersionPlaylist::create([
            'name' => $vplName,
            'slug' => Str::slug($vplName),
            'version' => 1,
            'playlist_id' => $screen->playlist->id,
            'state' => 1,
          ]);

          //se agrega copntenido nuevo
          $newPlaylistDetail = VersionPlaylistDetail::create([
            'version_playlist_id' => $newVPlaylistActive->id,
            'content_id' => $content->id,
            'orderContent' => $content->id,
          ]);
        }
      } else {

      }
    }
    return redirect(route('events.show', $event));
  }

  public function showAssign(Event $event, Content $content, Request $request)
  {
    $screens = Screen::whereHas('playlist', function ($query) use ($content) {
      $query->whereHas('versionPlaylists', function ($query) use ($content) {
				$query->whereHas('versionPlaylistDetails', function ($query) use ($content) {
					$query->where('content_id', $content->id);
					// dd($query->where('content_id', $content->id)->get());

				});
      });
    })->get();

		//dd($screens);

    return view('events.assignations.showAssignations', compact('event', 'content','screens'));
  }

  public function ScreenPlaylistAsign(Request $request, $id)
  {
    $content = Content::where('id', $id)->first();
    $screen = $this->screenRepository->all();
    if ($request->pantallas != null) {
      foreach ($request->pantallas as $idScreen) {
        $playlist_asign = ScreenPlaylistAsignation::where('screen_id', $idScreen)->get();
        if ($playlist_asign != null) {
          $version_playlist_detail = new VersionPlaylistDetail;
          $playlist_asign = ScreenPlaylistAsignation::where('screen_id', $idScreen)->get();
          foreach ($playlist_asign as $playlist_asigns) {
            $version_playlist_detail->version_id = $playlist_asigns->version_id;
          }
          $version_playlist_detail->content_id = $id;
          $version_playlist_detail->save();
        }
      }
      Flash::success('pantalla asignada con contenido');
      return redirect(url()->previous());
    } else {
      Flash::error('no ha seleccionado ninguna pantalla');
      return redirect(url()->previous());
    }
  }

  public function indexClient()
  {
    $content = Content::all()->where('event_id', 1);
    $lists = Event::all();

    if (empty($event)) {
      Flash::error('Evento no encontrado');

      return redirect(route('contents.index'));
    }

    return view('events.index', compact('event, lists'));
  }

  public function showClient(Event $event, Request $request)
  {
		//comprobamos que el evento tenga contenido
		if($event->contents->count()!=0){
			//obtenemos los contenidos del evento
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
			//extraemos las pantallas
			$screens= screen::find($list);
			return view('client.events.show', compact('event'))->with('screens',$screens);
		}else{
			$screens= $event->contents;
    	return view('client.events.show', compact('event'))->with('screens',$screens);
		}

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
      $version_playlist_details = VersionPlaylistDetail::where('content_id', $content->id)->get();
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
    $company = Company::where('id',  auth()->user()->company_id)->first();
    $listsStore = Store::all();
    if ($filter != null || $filterState != null || $filterDate != null || $filterDateEnd != null) {
      if ($filter != null) {
      $events = Event::where('company_id', auth()->user()->company_id)->where('name', 'LIKE', "%$filter%")->orderBy('state', 'asc')->paginate();
      }
      if ($filterState != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('state', $filterState)->orderBy('state', 'asc')->paginate();
      }
      if ($filterDate != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('initdate', 'LIKE', "%$filterDate%")->orderBy('state', 'asc')->paginate();
      }
      if ($filterDateEnd != null) {
        $events = Event::where('company_id', auth()->user()->company_id)->where('enddate', 'LIKE', "%$filterDateEnd%")->orderBy('state', 'asc')->paginate();
      }
      if(count($events)==0){
        Flash::info('No se encontro ningun resultado.');
        return redirect(url()->previous());
      }
      return view('events.index', compact('events', 'listsStore'))->with('company', $company);
    }else {
    Flash::error('Ingrese un valor para generar la busqueda.');
    return redirect(url()->previous());
    }
  }
  // public function filter_by_name(Request $request)
  // {
  //   $eventsFinal = null;
  //   $filter = $request->get('nameFiltrar');
  //   $filterState = $request->get('state');
  //   $filterDate = $request->get('initdate');
  //   $filterDateEnd = $request->get('enddate');
  //   $filterSector = $request->get('sector');
  //   $filterFloor = $request->get('floor');
  //   $filterType = $request->get('type');
  //   if ($filter != null) {
  //     $events = Event::where('name', 'LIKE', "%$filter%")->paginate();
  //   } else {
  //     if ($filterState != null) {
  //       $events = Event::where('state', $filterState)->paginate();
  //     } else {
  //       if ($filterDate != null) {
  //         $events = Event::where('initdate', $filterDate)->paginate();
  //       } else {
  //         if ($filterDateEnd != null) {
  //           $events = Event::where('enddate', $filterDateEnd)->paginate();
  //         } else {
  //           $filter = $request->get('nameFiltrar');
  //           $filterSector = $request->get('sector');
  //           $filterFloor = $request->get('floor');
  //           $filterType = $request->get('type');
  //           $filterState = $request->get('state');
  //           $filterStore = $request->get('store');
  //           $i = 0;
  //           $a = 0;
  //           $version_playlist_details = null;
  //           $screen_playlist_asignations = null;
  //           $events = Event::all();
  //           foreach ($events as $event) {
  //             $screens2 = array();
  //             $contents = Content::where('event_id', $event->id)->paginate();
  //             foreach ($contents as $content) {
  //               $version_playlist_details = VersionPlaylistDetail::where('content_id', $content->id)->get();
  //               if ($version_playlist_details != null) {
  //                 foreach ($version_playlist_details as $version_playlist_detail) {
  //                   $screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('version_id', $version_playlist_detail->version_id)->get();
  //                   if ($screen_playlist_asignations != null) {
  //                     foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
  //                       if ($filterSector != null) {
  //                         $screens = Screen::where([
  //                           ['id', $screen_playlist_asignation->screen_id],
  //                           ['sector', $filterSector],
  //                         ])->first();
  //                         if ($screens != null) {
  //                           $eventsFinal[$i] = $event;
  //                           $i++;
  //                         }
  //                       } else {
  //                         if ($filterFloor != null) {
  //                           $screens = Screen::where([
  //                             ['id', $screen_playlist_asignation->screen_id],
  //                             ['floor', $filterFloor],
  //                           ])->first();
  //                           if ($screens != null) {
  //                             $eventsFinal[$i] = $event;
  //                             $i++;
  //                           }
  //                         } else {
  //                           if ($filterType != null) {
  //                             $screens = Screen::where([
  //                               ['id', $screen_playlist_asignation->screen_id],
  //                               ['type', $filterType],
  //                             ])->first();
  //                             if ($screens != null) {
  //                               $eventsFinal[$i] = $event;
  //                               $i++;
  //                             }
  //                           } else {
  //                             if ($filterStore != null) {
  //                               $screens = Screen::where([
  //                                 ['id', $screen_playlist_asignation->screen_id],
  //                               ])->first();
  //                               if ($screens != null) {
  //                                 $computer = Computer::where('id', $screens->computer_id)->first();
  //                                 if ($computer != null) {
  //                                   $store = Store::where([
  //                                     ['id', $computer->store_id],
  //                                     ['id', $filterStore],
  //                                   ])->first();
  //                                   if ($store != null) {
  //                                     $eventsFinal[$i] = $event;
  //                                     $i++;
  //                                   }
  //                                 }
  //                               }
  //                             }
  //                           }
  //                         }
  //                       }
  //                     }
  //                   }
  //                 }
  //               }
  //             }
  //           }
  //         }
  //       }
  //     }
  //     $lists = Event::all();
  //     $listsStore = Store::all();
  //     if ($eventsFinal != null) {
  //       return view(
  //         'events.index',
  //         ['events' => $eventsFinal],
  //         compact('lists', 'listsStore')
  //       );
  //     } else {
  //       if ($events != null) {
  //         if ($filter != null) {
  //           return view(
  //             'events.index',
  //             ['events' => $events],
  //             compact('lists', 'listsStore')
  //           );
  //         } else {
  //           return view(
  //             'events.index',
  //             ['events' => $events],
  //             compact('lists', 'listsStore')
  //           );
  //         }
  //       }
  //     }
  //   }
  // }

  //index cliente eventos
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
      $version_playlist_details = VersionPlaylistDetail::where('content_id', $content->id)->get();
      if ($version_playlist_details != null) {
        foreach ($version_playlist_details as $version_playlist_detail) {
          $screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('version_id', $version_playlist_detail->version_id)->get();
          if ($screen_playlist_asignations != null) {
            foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
              if ($filter != null) {
                $screens = Screen::where([
                  ['id', $screen_playlist_asignation->screen_id],
                  ['name', 'LIKE', "%$filter%"],
                ])->first();
              } else {
                if ($filterSector != null) {
                  $screens = Screen::where([
                    ['id', $screen_playlist_asignation->screen_id],
                    ['sector', $filterSector],
                  ])->first();
                } else {
                  if ($filterFloor != null) {
                    $screens = Screen::where([
                      ['id', $screen_playlist_asignation->screen_id],
                      ['floor', $filterFloor],
                    ])->first();
                  } else {
                    if ($filterType != null) {
                      $screens = Screen::where([
                        ['id', $screen_playlist_asignation->screen_id],
                        ['type', $filterType],
                      ])->first();
                    } else {
                      if ($filterState != null) {
                        $screens = Screen::where([
                          ['id', $screen_playlist_asignation->screen_id],
                          ['state', $filterState],
                        ])->first();
                      } else {
                        $screens = Screen::where([
                          ['id', $screen_playlist_asignation->screen_id],
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
