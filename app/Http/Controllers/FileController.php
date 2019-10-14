<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str as Str;
use App\Repositories\ContentRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
use getid3;
use File;
use App\Models\Event;
use App\Models\Content;

class FileController extends Controller
{
	private $contentRepository;

  public function __construct(ContentRepository $contentRepo)
  {
    $this->contentRepository = $contentRepo;
  }
	public function store(Request $request,$id)
	{
		$content= Content::where('event_id',$id)->paginate();
		$event = Event::where('id', $id)->paginate();
		$eventName = Event::where('id', $id)->first();
		$files = $request->file('file');
		$path = public_path().'/storage/app/videos/'. $eventName->name;
		File::makeDirectory($path, $mode = 0777, true, true);
		if ($request->hasFile('file')) {
			//Rescata valores de los archivos subidos
			foreach ($files as $file) {
				$getID3 = new \getID3;
				$fileX = $getID3->analyze($file);

			$filetype = $file->getClientOriginalExtension();
			$mime = $file->getClientMimeType();
			$user_id=Auth::user()->id;
			$size = $file->getSize();
			$width = $fileX['video']['resolution_x'];
			$height = $fileX['video']['resolution_y'];
			$name= Str::slug(str_replace("_"," ",$eventName->name.'_'.$width.'X'.$height));
			$original_name = Str::slug(str_replace("_"," ",$eventName->name.'_'.$width.'X'.$height));
			$slug = Str::slug(str_replace("_", "", $name));
			$file->move($path, $original_name.'.mp4');

			//TODO: verificar si tiene una lista asignada para cambiar el path dir a la lista correspondiente
			$request->merge(['user_id'=>$user_id]);
			$request->merge(['location'=>$path]);
			$request->merge(['original_name'=>$original_name]);
			$request->merge(['slug'=>$slug]);
			$request->merge(['filetype'=>$filetype]);
			$request->merge(['mime'=>$mime]);
			$request->merge(['event_id'=>$id]);
			$request->merge(['size'=>$size]);
			$request->merge(['width'=>$width]);
			$request->merge(['height'=>$height]);
			$request->merge(['name'=>$name]);
			$input = $request->all();
      $content = $this->contentRepository->create($input);
		}
	}

		return redirect()->route('events.index');

	}

	public function index()
	{

		return redirect()->route('events.index');
	}
}
