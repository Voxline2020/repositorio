<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use App\Repositories\PlaylistRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\VersionPlaylist;
use App\Models\VersionPlaylistDetail;
use App\Models\Playlist;
use App\Models\Content;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PlaylistController extends AppBaseController
{
    /** @var  PlaylistRepository */
    private $playlistRepository;

    public function __construct(PlaylistRepository $playlistRepo)
    {
        $this->playlistRepository = $playlistRepo;
    }

    /**
     * Display a listing of the Playlist.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
                $playlists = Playlist::wherehas('versionPlaylists', function ($query) {
                    $query->whereHas('versionPlaylistDetails', function ($query) {
                    });
                })->paginate();
				$version= VersionPlaylist::where('state','1')->first();
                $version_details= VersionPlaylistDetail::where('version_playlist_id',$version->id)->get();   
				$url=array();
				$i=0;
				foreach($version_details as $version_detail){
					$url[$i]= '/video/'.$version_detail->id.'/d';
					$i++;
				}
				$json = json_encode($url,  JSON_PRETTY_PRINT);

				$json_1=json_decode($json, true);
                //  dd($version_details);


        return view('playlists.index')
            ->with('playlists', $playlists);
    }

    /**
     * Show the form for creating a new Playlist.
     *
     * @return Response
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Store a newly created Playlist in storage.
     *
     * @param CreatePlaylistRequest $request
     *
     * @return Response
     */
    public function store(CreatePlaylistRequest $request)
    {
        $input = $request->all();

        $playlist = $this->playlistRepository->create($input);

        Flash::success('Playlist saved successfully.');

        return redirect(route('playlists.index'));
    }

    /**
     * Display the specified Playlist.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // $playlist = $this->playlistRepository->find($id);
        $playlist = Playlist::wherehas('versionPlaylists', function ($query) {
            $query->whereHas('versionPlaylistDetails', function ($query) {
                $query->whereHas('content', function ($query) {
                });
            });
        })->find($id);
        $list = [];
        foreach($playlist->versionPlaylists AS $version){
            foreach($version->versionPlaylistDetails AS $detail){
                array_push($list,$detail->content_id);
            }
        }
        $contents = Content::find($list);
        if (empty($playlist)) {
            Flash::error('No se encontro el Playlist');

            return redirect(route('playlists.index'));
        }

        return view('playlists.show')->with('playlist', $playlist)->with('contents', $contents);
    }

    /**
     * Show the form for editing the specified Playlist.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playlist = $this->playlistRepository->find($id);

        if (empty($playlist)) {
            Flash::error('Playlist not found');

            return redirect(route('playlists.index'));
        }

        return view('playlists.edit')->with('playlist', $playlist);
    }

    /**
     * Update the specified Playlist in storage.
     *
     * @param  int              $id
     * @param UpdatePlaylistRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlaylistRequest $request)
    {
        $playlist = $this->playlistRepository->find($id);

        if (empty($playlist)) {
            Flash::error('Playlist not found');

            return redirect(route('playlists.index'));
        }

        $playlist = $this->playlistRepository->update($request->all(), $id);

        Flash::success('Playlist updated successfully.');

        return redirect(route('playlists.index'));
    }

    /**
     * Remove the specified Playlist from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {

        $playlist = $this->playlistRepository->find($id);

        if (empty($playlist)) {
            Flash::error('Playlist not found');

            return redirect(route('playlists.index'));
        }

        $this->playlistRepository->delete($id);

        Flash::success('Playlist deleted successfully.');

        return redirect(route('playlists.index'));
    }
}
