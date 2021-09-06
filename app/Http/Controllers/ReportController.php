<?php

namespace App\Http\Controllers;

use App\Models\VersionPlaylistDetail;
use App\Models\ScreenPlaylistAsignation;
use App\Models\Screen;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\Computer;
use App\Models\Event;
use App\Models\VersionPlaylist;
use Flash;

class ReportController extends Controller
{
	public function generate($id)
	{
		//recorre los modelos para poder obtener las pantallas y contenidos asociados a el evento.
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
					$contentsx = Content::where('id', $version_playlist_detail->content_id)->first();
					if ($screen_playlist_asignations != null) {
						foreach ($screen_playlist_asignations as $screen_playlist_asignation) {
							$screens = Screen::where('id', $screen_playlist_asignation->screen_id)->first();
							if($screens!=null){
								$screens2[$i] = $screens;
								$screens2[$i]['content'] = $contentsx->name;
								$i++;
							}
						}
					}
				}
			}
		}

		if ($version_playlist_details != null || $screen_playlist_asignations != null || $screens2 != null) {

			$screens2 = collect($screens2)->sortBy('name')->reverse();
			$view = \View::make('pdf.report', compact( 'screens2','event'))->render();
			$pdf = \App::make('dompdf.wrapper');
			$pdf->loadHTML($view)->setPaper('a4', 'landscape');;
			return $pdf->stream('informe' . '.pdf'); //transforma el resultado a pdf.
		} else {
			Flash::error('no hay pantallas asignadas para este evento');
			return redirect(url()->previous());
		}
	}
	public function generateContent()
	{
		//genera el pdf para los contenidos.
		$contents = Content::all();
		if ($contents != null) {
			$view = \View::make('pdf.reportContent', compact('contents'))->render();
			$pdf = \App::make('dompdf.wrapper');
			$pdf->loadHTML($view)->setPaper('a4', 'landscape');;
			return $pdf->stream('informe_Contenido' . '.pdf');
		} else {
			Flash::error('no hay contenidos');
			return redirect(url()->previous());
		}
	}
}
