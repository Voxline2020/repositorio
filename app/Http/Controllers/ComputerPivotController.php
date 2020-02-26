<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Company;
use App\Models\Computer;
use App\Models\ComputerOnPivot;
use App\Models\ComputerPivot;
use App\Models\Content;
use App\Models\Store;
use App\Models\Event;
use App\Repositories\ComputerPivotRepository;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Response;

class ComputerPivotController extends AppBaseController
{
  /** @var  StoreRepository */
  private $computerPivotRepository;

  public function __construct(ComputerPivotRepository $computerPivotRepo)
  {
    $this->middleware('auth')->except('getInfo');
    $this->computerPivotRepository = $computerPivotRepo;
  }
  public function index(Request $request)
  {
    $companies = Company::all();
    $stores = Store::all();
    $pivots = ComputerPivot::paginate();
    return view('pivots.index')->with('pivots', $pivots)->with('companies', $companies)
      ->with('stores', $stores);
  }
  public function show($id)
  {
    $pivot = ComputerPivot::find($id);
    $onpivots = ComputerOnPivot::where('computer_pivot_id', $id)->paginate();
    $computers = Computer::whereHas('store', function ($query) use ($pivot) {
      $query->where('company_id', $pivot->company_id);
    })->paginate();
    return view('pivots.show')->with('pivot', $pivot)->with('onpivots', $onpivots)->with('computers', $computers);
  }

  public function create()
  {
    $pivots = ComputerPivot::all();
    $companies = Company::all();
    $stores = store::all();
    return view('pivots.create')->with('pivots', $pivots)->with('companies', $companies)->with('stores', $stores);
  }

  public function store(Request $request)
  {
    $validate = ComputerPivot::where('name', $request->name)->where('company_id', $request->company_id);
    if ($validate->count() != 0) {
      Flash::error('Ese nombre de pivote ya existe.');
      return redirect(route('pivots.create'));
    }
    $input = $request->all();
    $pivot = $this->computerPivotRepository->create($input);
    Flash::success('Computador pivote agregado con exito.');
    return redirect(route('pivots.index'));
  }

  public function edit($id)
  {

    $companies = Company::all();
    $stores = store::all();
    $pivot = ComputerPivot::find($id);
    if (empty($pivot)) {
      Flash::error('Computador pivote no encontrado');
      return redirect(route('pivots.index'));
    }
    return view('pivots.edit')->with('pivot', $pivot)->with('companies', $companies)->with('stores', $stores);
  }

  public function update($id, Request $request)
  {
    $pivot = $this->computerPivotRepository->find($id);
    if (empty($pivot)) {
      Flash::error('Computador pivote no encontrado');
      return redirect(route('pivots.index'));
    }
    $pivot = $this->computerPivotRepository->update($request->all(), $id);
    Flash::success('Computador pivote editado');
    return redirect(route('pivots.index'));
  }
  public function destroy($id)
  {
    $pivot = $this->computerPivotRepository->find($id);
    if (empty($pivot)) {
      Flash::error('Computador pivote no encontrado');
      return redirect(route('pivots.index'));
    }
    $this->computerPivotRepository->delete($id);
    Flash::success('Computador pivote borrado');
    return redirect(route('pivots.index'));
  }
  public function storeOnpivot($id, Request $request)
  {
    $validate = ComputerOnPivot::where('computer_id', $request->computer_id)->where('computer_pivot_id', $request->computer_pivot_id);
    if ($validate->count() != 0) {
      Flash::error('Computador ya asignado en este pivote.');
      return redirect()->route('pivots.show', [$id]);
    }
    $input = $request->all();
    ComputerOnPivot::create($input);
    Flash::success('Computador agregado con exito.');
    return redirect()->route('pivots.show', [$id]);
  }
  public function destroyOnpivot($id)
  {
    $onpivot = ComputerOnPivot::find($id);
    if (empty($onpivot)) {
      Flash::error('Computador no encontrado');
      return redirect()->route('pivots.show', [$onpivot->computer_pivot_id]);
    }
    $onpivot->delete();
    Flash::success('Asignacion eliminada.');
    return redirect()->route('pivots.show', [$onpivot->computer_pivot_id]);
  }
  public function filter_by(Request $request)
  {
    $companies = Company::all();
    $stores = Store::all();
    $pivots = ComputerPivot::paginate();
    if ($request->nameFiltrar == null && $request->codeFiltrar == null && $request->company == null && $request->store == null) {
      Flash::error('Debes ingresar almenos un filtro para la busqueda.');
      return redirect(route('pivots.index'));
    }
    if ($request->company != null) {
      $pivots = ComputerPivot::where('company_id', $request->company)->paginate();
    }
    if ($request->store != null) {
      $pivots = ComputerPivot::where('location', $request->store)->paginate();
    }
    if ($request->codeFiltrar != null) {
      $pivots = ComputerPivot::where('code', 'like', "%$request->codeFiltrar%")->paginate();
    }
    if ($request->nameFiltrar != null) {
      $pivots = ComputerPivot::where('name', 'like', "%$request->nameFiltrar%")->paginate();
    }
    // dd($request);
    if ($pivots->count() == 0) {
      Flash::error('No se encontro ningun resultado.');
    }
    return view('pivots.index')->with('pivots', $pivots)->with('companies', $companies)->with('stores', $stores);
  }
  public function getInfo($code, $pass)
  {
    $pivot = ComputerPivot::with(['onpivots', 'onpivots.computer', 'onpivots.computer.screens', 'onpivots.computer.screens'])->where('code', $code)->where('pass', $pass)->first();
    if (isset($pivot)) {
      $jsonResponse = [];
      $jsonResponse['code'] = $pivot->code;
      foreach ($pivot->onpivots as $key => $onpivot) {
        $jsonResponse['computers'][$key]['code'] = $onpivot->computer->code;
        foreach ($onpivot->computer->screens as $key2 => $screen) {
          $jsonResponse['computers'][$key]['screens'][$key2]['code'] = $screen->id;
          $jsonResponse['computers'][$key]['screens'][$key2]['name'] = $screen->name;
          $jsonResponse['computers'][$key]['screens'][$key2]['width'] = $screen->width;
          $jsonResponse['computers'][$key]['screens'][$key2]['height'] = $screen->height;
          $jsonResponse['computers'][$key]['screens'][$key2]['state'] = $screen->state;
          $jsonResponse['computers'][$key]['screens'][$key2]['version'] = $screen->version;

					$aux_eventAssignations = $screen->eventAssignations->where('state', 1);
          $i = 0;
          foreach ($aux_eventAssignations as $eventAsignation) {
						$event = Event::find($eventAsignation->content->event_id);
            $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['defOrder'] = $eventAsignation->order;
            $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['originalID'] = $eventAsignation->content->id;
            $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['name'] = $eventAsignation->content->name;
            $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['width'] = $eventAsignation->content->width;
						$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['height'] = $eventAsignation->content->height;
						$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['initdate'] = Carbon::parse($event->initdate)->format('d/m/Y H:i');
						$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['enddate'] = Carbon::parse($event->enddate)->format('d/m/Y H:i');
						$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['deleted'] = empty($eventAsignation->content->deleted_at) ? null : Carbon::parse($eventAsignation->content->deleted_at)->format('d/m/Y H:i');
            $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$i]['download'] = route('contents.download', $eventAsignation->content->id);
            $i++;
          }
          // foreach ($screen->playlist->versionPlaylists as $versionPlaylist); {
          //   if ($versionPlaylist->state == 1) {
          //     $jsonResponse['computers'][$key]['screens'][$key2]['version'] = $versionPlaylist->version;
          //     $vPlaylistDetails = $versionPlaylist->versionPlaylistDetails;
          //     foreach ($vPlaylistDetails as $key3 => $vPlaylistDetail) {
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['defOrder'] = $key3;
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['originalID'] = $vPlaylistDetail->contentWithTrashed->id;
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['name'] = $vPlaylistDetail->contentWithTrashed->name;
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['width'] = $vPlaylistDetail->contentWithTrashed->width;
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['height'] = $vPlaylistDetail->contentWithTrashed->height;
          //       $jsonResponse['computers'][$key]['screens'][$kesyny2]['playlist'][$key3]['deleted'] = empty($vPlaylistDetail->contentWithTrashed->deleted_at) ? null : Carbon::parse($vPlaylistDetail->contentWithTrashed->deleted_at)->format('d/m/Y H:i');
          //       $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['download'] = route('contents.download', $vPlaylistDetail->contentWithTrashed->id);
          //     }
          //   }
          // }
        }
      }
      //return dump($jsonResponse);
      return response()->json($jsonResponse);
    } else {
      return abort(404);
    }
  }

}
