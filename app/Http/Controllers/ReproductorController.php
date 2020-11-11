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
}
