<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateScreenRequest;
use App\Http\Requests\UpdateScreenRequest;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Device;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\Screen;
use App\Models\ScreenPlaylistAsignation;
use App\Models\Store;
use App\Models\VersionPlaylistDetail;
use App\Repositories\ScreenRepository;
use App\Repositories\VersionPlaylistDetailRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;

class ScreenController extends AppBaseController
{
  /** @var  ScreenRepository */
  private $screenRepository;
  private $versionPlaylistDetailRepository;
  public function __construct2(VersionPlaylistDetailRepository $screenRepo2)
  {
    $this->versionPlaylistDetailRepository = $screenRepo2;
  }

  public function __construct(ScreenRepository $screenRepo)
  {
    $this->middleware('auth')->except(['apiIndex','apiView','apiPost','apiPut','apiGetDeviceVersion']);
    $this->middleware('admin')->except(['AssignContent', 'ScreenPlaylistAsign', 'changeStatus', 'filter_by_name', 'eventAssign', 'changeOrder', 'cloneEvent', 'apiIndex','apiView', 'apiPost','apiPut','apiGetDeviceVersion']);
    $this->screenRepository = $screenRepo;
  }
  //mostrar pantallas
  public function index(Request $request)
  {
    $computers = Computer::all();
    $screens = $this->screenRepository->all();
    return view('screen.index')
      ->with('screens', $screens)
      ->with('computers', $computers);
  }
  //mostrar pantallas con id en especifico
  public function show($id)
  {
    $computer = Computer::where('id', $id)->first();
    $screen = Screen::where('computer_id', $id)->paginate();
    return view(
      'screen.index',
      ['screens' => $screen],
      compact('computer')
    );
  }
  public function AssignContent($id)
  {
    $content = Content::where('id', $id)->first();
    $screen = $this->screenRepository->all();
    return view(
      'screen.index_content_screen',
      ['screens' => $screen],
      compact('content')
    );
  }

  public function ScreenPlaylistAsign(Request $request, $id)
  {
    $content = Content::where('id', $id)->first();
    $screen = $this->screenRepository->all();
    if ($request->pantallas != null) {
      foreach ($request->pantallas as $idScreen) {
        $playlist_asign = ScreenPlaylistAsignation::where('screen_id', $idScreen)->get();
        if ($playlist_asign != null) {
          $version_playlist_detail = new VersionPlaylistDetail();
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
  //creacion screens
  //vista de creacion
  public function create($id)
  {
    $computer = Computer::where('id', $id)->get();
    return view('screen.create')->with('computer', $computer);
  }
  //Request de creacion (POST)
  public function store(CreateScreenRequest $request)
  {
    $input = $request->all();
    $screen = $this->screenRepository->create($input);
    Flash::success('Pantalla agregada con exito.');
    return redirect(url()->previous());
  }
  //editar screens
  //vista de edicion.
  public function edit($id, $computer_id)
  {
    $computers = Computer::where('id', $computer_id)->get();
    $screen = $this->screenRepository->find($id);

    if (empty($screen)) {
      Flash::error('Tienda no encontrada');
      return redirect(route('screens.index'));
    }
    return view('screen.edit', compact('computers'))->with('screen', $screen);
  }
  //Request de edicion (POST)
  public function update($id, UpdateScreenRequest $request)
  {
    $screen = $this->screenRepository->find($id);
    if (empty($screen)) {
      Flash::error('Pantalla no encontrada');
      return redirect(url()->previous());
    }
    $screen = $this->screenRepository->update($request->all(), $id);
    Flash::success('Pantalla editada');
    return redirect(url()->previous());
  }
  //Eliminar screens
  public function destroy($id)
  {
    $screen = $this->screenRepository->find($id);
    if (empty($screen)) {
      Flash::error('pantalla no encontrada');
      return redirect(url()->previous());
    }
    $this->screenRepository->delete($id);
    Flash::success('Pantalla borrada');
    return redirect(url()->previous());
  }
//filtros.
  public function filter_by_name(Request $request, $id)
  {
    $content = Content::where('id', $id)->first();
    $filter = $request->get('nameFiltrar');
    $screen = Screen::where('name', 'LIKE', "%$filter%")->paginate();
    return view(
      'screen.index_content_screen',
      ['screens' => $screen],
      compact('content')
    );
  }
  public function changeStatus(Request $request, $id)
  {
    $screen = $this->screenRepository->find($id);
    $screen->state = $request['state'];
    if (empty($request)) {
      Flash::error('Error');
      return redirect(url()->previous());
    }
    $screen->save();
    Flash::success('Estado actualizado');
    return redirect(url()->previous($screen));
  }
  public function eventAssign($id, Request $request)
  {
    $request->merge(['slug' => Str::slug($request['name'])]);
    $screen = Screen::find($id);
    $events = Event::find($request->event_id);

    foreach ($events as $event) {
      $contents = Content::where('event_id', $event->id)
        ->where('width', $screen->width)
        ->where('height', $screen->height)
        ->get();
      // if($contents->count()!=1){
      foreach ($contents as $content) {
        $count_assigns = EventAssignation::where('screen_id', $screen->id)->where('state', 1)->count() + 1;
        $request->merge([
          "screen_id" => $screen->id,
          "state" => $event->state,
          "content_id" => $content->id,
          "order" => $count_assigns,
        ]);
        $screen->version = $screen->version + 1;
        $screen->save();
        $input = $request->all();
        EventAssignation::create($input);
      }
      Flash::success('Evento "' . $event->name . '" asignado exitosamente');
    }
    return redirect(url()->previous($screen));
  }
  public function changeOrder(Request $request)
  {
    // validaciones
    if ($request->neworder == null) {
      Flash::error('Debes ingresar un nuevo Nº de orden.');
      return redirect(url()->previous());
    }
    if ($request->screen == null) {
      Flash::error('No se ha podido realizar la operación.');
      return redirect(url()->previous());
    }
    if ($request->id == null) {
      Flash::error('No se ha podido realizar la operación.');
      return redirect(url()->previous());
    }
    //llamado de objeto inicial
    $objIni = EventAssignation::find($request->id);
    //traemos la pantalla
    $screen = Screen::find($request->screen);
    //si la nueva posicion y la posicion actual son iguales
    if ($request->neworder == $objIni->order) {
      Flash::error('La nueva posicion no puede ser igual a la actual.');
      return redirect(url()->previous());
    }
    //si la nueva posicion excede el rango de elementos
    $countObjs = EventAssignation::where('screen_id', $screen->id)->get();
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
    if ($objIni->order < $request->neworder) {
      $listobjs = EventAssignation::where('screen_id', $screen->id)
        ->where('order', '<=', $request->neworder)
        ->where('order', '>', $objIni->order)
        ->orderby('order', 'ASC')
        ->where('state', 1)
        ->get();
    } else if ($objIni->order > $request->neworder) {
      $listobjs = EventAssignation::where('screen_id', $screen->id)
        ->where('order', '>=', $request->neworder)
        ->where('order', '<', $objIni->order)
        ->orderby('order', 'ASC')
        ->where('state', 1)
        ->get();
    }
    //intercambio de orden y guardado
    $order = $objIni->order;
    $neworder = $request->neworder;
    $objIni->order = $neworder;
    foreach ($listobjs as $objs) {
      if ($order < $neworder) {
        $objs->order = $order;
        $order = $order + 1;
      } else if ($order > $neworder) {
        $neworder = $neworder + 1;
        $objs->order = $neworder;
      }
      $objs->save();
    }
    $objIni->save();
    Flash::success('Cambio de orden realizado.');
    return redirect(url()->previous());
  }
  public function cloneEvent(Request $request)
  {
    //asignamos el order para el clon
    $count_assigns = EventAssignation::where('screen_id', $request->screen_id)->where('state', 1)->count() + 1;
    $request->merge([
      "order" => $count_assigns,
    ]);
    // extraemos los datos del elemento original
    $input = $request->all();
    //cambiamos version en pantalla
    $screen = Screen::find($request->screen_id);
    $screen->version = $screen->version + 1;
    $screen->save();
    //creamos el nuevo elemento clonado
    EventAssignation::create($input);
    Flash::success('Se ha clonado el elemento correctamente.');
    return redirect(url()->previous());

  }

  public function apiGetDeviceVersion(Request $request, $code, $key)
	{
    $device = Device::where('code' ,'=', $code);
    $jsonResponse = [];
		$jsonResponse['version'] = json_encode($code);
    return response()->json($jsonResponse);
  }

  public function apiIndex(Request $request)
  {
    $screens = Screen::all();
    return $screens;
  }

  public function apiView(Request $request, $code)
  {
		$screen = Device::where('code', $code)->get()->first();
		return json_encode(screen);
  }

	public function apiPut(Request $request, $code)
	{
		$device = Device::where('code',$code)->get()->first();
		if(!empty($device)){
			$device->name = $request['name'];
			$device->height = $request['height'];
			$device->width = $request['width'];
			$device->computer_id = $request['computer_id'];
			$device->state = $request['state'];
			$device->type_id = $request['type_id'];
			if(isset($request['version']) && !empty($request['version'])){
				$device->version = $request['version'];
			}
			$device->code = $request['code'];
			if($device->save()){
				$jsonResponse = [];
				$jsonResponse['response'] = "new device updated";
				return response()->json($jsonResponse);

			}
			else{
				$jsonResponse = [];
				$jsonResponse['response'] = "error save";
				return response()->json($jsonResponse);
			}
		}
		else{
			$jsonResponse = [];
			$jsonResponse['response'] = "null";
			return response()->json($jsonResponse);
		}


	}

	public function apiPost(Request $request, $code)
	{
		$device = new Device();
		$device->name = $request['name'];
		$device->height = $request['height'];
		$device->width = $request['width'];
		$device->computer_id = $request['computer_id'];
		$device->state = $request['state'];
		$device->type_id = $request['type_id'];
		if(isset($request['version']) && !empty($request['version'])){
			$device->version = $request['version'];
		}
		$device->code = $request['code'];
		if($device->save()){
			$jsonResponse = [];
			$jsonResponse['response'] = "new device added";
			return response()->json($jsonResponse);

		}
		else{
			$jsonResponse = [];
			$jsonResponse['response'] = "error";
			return response()->json($jsonResponse);
		}
	}

}
