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
    $pivot = ComputerPivot::where('code', $code)->where('pass', $pass)->first();
    if (isset($pivot)) {
      $jsonResponse = [];
      $jsonResponse['code'] = $pivot->code;
      foreach ($pivot->computers as $computer) {
        $jsonResponse['computer']['code'] = $computer->code;
        foreach ($computer->screens as $screen) {
          $jsonResponse['screens']['id'] = $screen->id;
          $jsonResponse['screens']['name'] = $screen->name;
          $jsonResponse['screens']['width'] = $screen->width;
          $jsonResponse['screens']['height'] = $screen->height;
          foreach ($screen->playlist->versionPlaylists as $versionPlaylist) {
            if ($versionPlaylist->state == 1) {
              $jsonResponse['screens']['playlist']['version'] = $versionPlaylist->version;
              foreach ($versionPlaylist->versionPlaylistDetails as $key => $vPlaylistDetail) {
                $jsonResponse['screens']['playlist'][$key]['name'] = $vPlaylistDetail->content->name;
                $jsonResponse['screens']['playlist'][$key]['width'] = $vPlaylistDetail->content->width;
                $jsonResponse['screens']['playlist'][$key]['height'] = $vPlaylistDetail->content->height;
                $jsonResponse['screens']['playlist'][$key]['download'] = route('contents.download', $vPlaylistDetail->content->id);
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
