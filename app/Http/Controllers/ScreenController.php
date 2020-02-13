<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScreenRequest;
use App\Http\Requests\UpdateScreenRequest;
use App\Repositories\ScreenRepository;
use App\Repositories\VersionPlaylistDetailRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Store;
use App\Models\Screen;
use App\Models\Computer;
use App\Models\Content;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\VersionPlaylistDetail;
use App\Models\ScreenPlaylistAsignation;
use Carbon\Carbon;
use Flash;
use Response;

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
		$this->middleware('admin')->except(['AssignContent','ScreenPlaylistAsign','changeStatus','filter_by_name','eventAssign','changeOrder','cloneEvent']);
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
		}
		else {
			Flash::error('no ha seleccionado ninguna pantalla');
			return redirect(url()->previous());
		}
	}
	//creacion screens
	//vista de creacion
	public function create($id)
	{
		$computer = Computer::where('id', $id)->get();
		return view('screen.create')->with('computer',$computer);
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
	public function filter_by_name(Request $request,$id)
	{
		$content = Content::where('id', $id)->first();
		$filter= $request->get('nameFiltrar');
		$screen = Screen::where('name','LIKE',"%$filter%")->paginate();
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
		// $request->merge(['slug' => Str::slug($request['name'])]);
		$screen=Screen::find($id);
		$event = Event::find($request->event_id);
		$contents = Content::where('event_id',$request->event_id)
		->where('width',$screen->width)
		->where('height',$screen->height)
		->get();
		if($contents->count()!=1){
			foreach($contents AS $content){
				$request->merge([
				"screen_id"=> $id,
				"state"=>$event->state,
				"content_id"=>$content->id,
				]);
				$screen->version = $screen->version+1;
				$screen->save();
				$input = $request->all();
				EventAssignation::create($input);
			}
		}else{
			$request->merge([
				"screen_id"=> $id,
				"state"=>$event->state,
				"content_id"=>$contents[0]->id,
				]);
				$screen->version = $screen->version+1;
				$screen->save();
				$input = $request->all();
				EventAssignation::create($input);
		}
		Flash::success('Evento asignado exitosamente');
		return redirect(url()->previous($screen));
	}
	public function changeOrder(Request $request)
	{
		// validaciones
		if($request->neworder==null){
			Flash::error('Debes ingresar un nuevo Nº de orden.');
			return redirect(url()->previous());
		}
		if($request->screen==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		if($request->id==null){
			Flash::error('No se ha podido realizar la operación.');
			return redirect(url()->previous());
		}
		//traemos la asignacion
		$assign=EventAssignation::find($request->id);
		//traemos la pantalla
		$screen=Screen::find($request->screen);
		//asignamos los nuevos valores y guradamos
		$assign->order = $request->neworder;
		$screen->version = $screen->version+1;
		$assign->save();
		$screen->save();
		Flash::success('Se ha cambiado el Nº de orden correctamente.');
		return redirect(url()->previous());
	}
	public function cloneEvent(Request $request)
	{
		// extraemos los datos del elemento original
		$input = $request->all();
		//cambiamos version en pantalla
		$screen=Screen::find($request->screen_id);
		$screen->version = $screen->version+1;
		$screen->save();
		//creamos el nuevo elemento clonado
		EventAssignation::create($input);
		Flash::success('Se ha clonado el elemento correctamente.');
		return redirect(url()->previous());
	}
}
