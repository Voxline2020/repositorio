<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateComputerRequest;
use App\Http\Requests\UpdateComputerRequest;
use App\Models\ComputerPivot;
use App\Models\Store;
use App\Repositories\ComputerPivotRepository;
use Illuminate\Http\Request;
use Response;

class ComputerPivotController extends AppBaseController
{
  /** @var  StoreRepository */
  private $computerPivotRepository;

  public function __construct(ComputerPivotRepository $computerPivotRepo)
  {
    $this->computerPivotRepository = $computerPivotRepo;
  }
  public function index(Request $request)
  {

  }
  public function show($id)
  {

  }

  public function create()
  {

  }

  public function store(CreateComputerRequest $request)
  {

  }

  public function edit($id, $store_id)
  {

  }

  public function update($id, UpdateComputerRequest $request)
  {

  }
  public function destroy($id)
  {

  }

  public function getInfo($code, $pass)
  {
    $pivot = ComputerPivot::with(['onpivots','onpivots.computer','onpivots.computer.screens'])->where('code', $code)->where('pass', $pass)->first();
    if (isset($pivot)) {
      $jsonResponse = [];
      $jsonResponse['code'] = $pivot->code;
      foreach($pivot->onpivots as $onpivot){
        foreach ($onpivot->computer->screens as $screen) {
          $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['name'] = $screen->name;
          $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['width'] = $screen->width;
          $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['height'] = $screen->height;
          foreach ($screen->playlist->versionPlaylists as $versionPlaylist); {
            if ($versionPlaylist->state == 1) {
              $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['playlist']['version'] = $versionPlaylist->version;
              foreach ($versionPlaylist->versionPlaylistDetails as $key => $vPlaylistDetail) {
                $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['playlist'][$key]['name'] = $vPlaylistDetail->content->name;
                $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['playlist'][$key]['width'] = $vPlaylistDetail->content->width;
                $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['playlist'][$key]['height'] = $vPlaylistDetail->content->height;
                $jsonResponse['computers'][$onpivot->computer->code]['screens'][$screen->id]['playlist'][$key]['download'] = route('contents.download', $vPlaylistDetail->content->id);
              }
            }
          }
        }
      }
      return response()->json($jsonResponse);
    }
		else {
      return abort(404);
    }
  }

}
