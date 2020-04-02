<?php

namespace App\Http\Controllers;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class DownloadContent extends Controller
{
    public function download($id){
			$content= Content::find($id);
			return response()->download($content->location.'/'.$content->original_name.'.mp4');
		}
}
