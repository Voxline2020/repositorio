<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReproductorController extends Controller
{
    public function index($storeName)
    {
        
        if(file_exists('storage/'.$storeName))
        {
            $videos = scandir('storage/'.$storeName);
            unset($videos[0]);
            unset($videos[1]);
            return view('reproductor.index')
            ->with('videos' , $videos)
            ->with('tienda', $storeName);
            
        }else
        {
            dd("tienda no encontrada");
        }


        return view('reproductor.index');
        
    }


    
	public function changueVideo(Request $request)
	{
        $data = $request->getContent();
        $video_name = $request->video;
        $tienda_name = $request->tienda;

        $videos = scandir('storage/'.$tienda_name);
        unset($videos[0]);
        unset($videos[1]);

            
        
        
        //$videoNameNuevo = $videos[2];
        if($video_name != $videos[2])
        {
            $success = "true"  ;
        }else
        {
            $success = "false" ;
        }

		

		//$jsondata['data'] =  $data;	
        $jsondata['sucess'] = $success;		 
        $jsondata['data'] = $data;
        $jsondata['video_name'] = $video_name;
        //$jsondata['video_name2'] = $videoNameNuevo;
        $jsondata['tienda_name'] = $tienda_name;
        $jsondata['videos'] = $videos;


		echo json_encode($jsondata);		 
		exit();
	}
}

