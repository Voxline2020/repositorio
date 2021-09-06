<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\VersionPlaylistDetail;
use App\Models\ScreenPlaylistAsignation;
use App\Models\Content;
use App\Models\Computer;
use App\Models\Event;
use App\Models\VersionPlaylist;

use Illuminate\Http\Request;

class ScreenJson extends Controller
{
	public function json($code)
	{
		$computer = Computer::where('code', $code)->first();
		$i = 0;
		$version_playlist_details = null;
		$screen_playlist_asignations = null;
		$screens = Screen::where('computer_id', $computer->id)->get();
		$screens2 = array();
		foreach ($screens as $screen) {
			$screen_playlist_asignations = \DB::table('screen_playlist_asignation')->where('screen_id', $screen->id)->get();
			if ($screen_playlist_asignations != null) {
				foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
					$version_playlist_details =  VersionPlaylistDetail::where('version_id', $screen_playlist_asignation->version_id)->get();
					$screenx = Screen::select('id')->where('id', $screen_playlist_asignation->screen_id)->first();
					if ($version_playlist_details!= null) {
						foreach ($version_playlist_details as $version_playlist_detail) {
							$content = Content::where('id', $version_playlist_detail->content_id)->first();
							if ($content != null) {
								$screens2[$i] = $screenx;
								$screens2[$i]['ruta'] = '/video/'.$content->id.'/d';
								$i++;

							}
						}
					}
				}
			}
		}
		dd($screens2);

	}

}
