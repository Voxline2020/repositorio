<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Repositories\ContentRepository;
use Flash;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;
use App\Models\Content;
use App\Models\Event;
use App\Models\Screen;
use App\Models\VersionPlaylistDetail;
use App\Models\ScreenPlaylistAsignation;
use Illuminate\Support\Facades\Redirect;

class ContentController extends Controller
{

	/** @var  UserRepository */
	private $contentRepository;

	public function __construct(ContentRepository $contentRepo)
	{
		$this->middleware('auth');
		$this->contentRepository = $contentRepo;
	}

	/**
	 * Display a listing of the Content.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$contents = $this->contentRepository->all();

		return view('contents.index')
			->with('contents', $contents);
	}

	/**
	 * Show the form for creating a new Content.
	 *
	 * @return Response
	 */
	public function create()
	{
		$events = Event::all();
		return view('contents.create', compact('events'));
	}

	/**
	 * Store a newly created Content in storage.
	 *
	 * @param CreateContentRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateContentRequest $request)
	{
		if ($request->hasFile('video')) {
			$slug = Str::slug(str_replace(".mp4", "", $request['name']));
			$original_name = str_replace(".mp4", "", $request->file('video')->getClientOriginalName());
			$filetype = $request->file('video')->getClientOriginalExtension();
			$mime = $request->file('video')->getClientMimeType();

			//TODO: verificar si tiene una lista asignada para cambiar el path dir a la lista correspondiente
			$path_dir = "sin-lista";

			$path = public_path() . '/uploads/';
			$path_storage = Storage::disk('videos')->put($path_dir, $request->file('video'));
			$request->merge(['location' => $path_storage]);
			$request->merge(['original_name' => $original_name]);
			$request->merge(['slug' => $slug]);
			$request->merge(['filetype' => $filetype]);
			$request->merge(['mime' => $mime]);
		}
		$input = $request->all();
		$content = $this->contentRepository->create($input);
		return redirect(route('contents.index'));
	}

	/**
	 * Display the specified Content.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$content = Content::where('event_id', $id)->paginate();
		return view(
			'contents.index',
			['contents' => $content]
		);
	}

	/**
	 * Show the form for editing the specified Content.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$events = Event::all();
		$content = $this->contentRepository->find($id);

		if (empty($content)) {
			Flash::error('Content not found');

			return redirect(route('contents.index'));
		}

		return view('contents.edit', compact('events'))->with('content', $content);
	}

	/**
	 * Update the specified Content in storage.
	 *
	 * @param  int              $id
	 * @param UpdateContentRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateContentRequest $request)
	{
		$content = $this->contentRepository->find($id);

		if (empty($content)) {
			Flash::error('Content not found');

			return redirect(route('contents.index'));
		}

		$content = $this->contentRepository->update($request->all(), $id);

		Flash::success('Contenido actualizado exitosamente');

		return Redirect::back();
	}

	/**
	 * Remove the specified Content from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$content = $this->contentRepository->find($id);

		if (empty($content)) {
			Flash::error('Content not found');

			return redirect(route('contents.index'));
		}

		$this->contentRepository->delete($id);

		Flash::success('Contenido eliminado exitosamente.');

		return redirect(url()->previous());
	}

	public function ScreenView($id)
	{
		$content = Content::where('id', $id)->first();
		$screen = array();
		$i = 0;
		$version_playlist_detail = VersionPlaylistDetail::where('content_id', $content->id)->first();
		if ($version_playlist_detail != null) {
			$screen_playlist_asignations = ScreenPlaylistAsignation::where('version_id', $version_playlist_detail->version_id)->get();
			if ($screen_playlist_asignations != null) {
				foreach ($screen_playlist_asignations as $screen_playlist_asignation) {

					$screen2 = Screen::where('id', $screen_playlist_asignation->screen_id)->first();
					if ($screen2 != null) {
						$screen[$i] = $screen2;
						$i++;
					}
				}

				return view(
					'contents.index_content_view',
					['screens' => $screen],
					compact('content')
				);
			}
			Flash::error('No hay pantallas asignadas a este contenido');
			return redirect(route('contents.index'));
		}
		Flash::error('No hay pantallas asignadas a este contenido');
		return redirect(route('contents.index'));
	}
}
