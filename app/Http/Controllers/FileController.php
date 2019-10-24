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

	public function index()
	{

		return redirect()->route('events.index');
	}
}
