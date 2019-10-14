<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Company;
use Doctrine\Common\Cache\RedisCache;

//

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dash()
    {
			if(Auth::user()->hasRole('Administrador')){
				$companies = Company::all();
				return view('companies.index',compact('companies'));
			}
			else if(Auth::user()->hasRole('Cliente')){
				$user = Auth::user()->name;
				$events = \App\Models\Event::all();
				$screens= \App\Models\Screen::all();
				$modelComputer = \App\Models\Computer::all();
				$modelStore = \App\Models\Store::all();
				$screenActive = \App\Models\Screen::where('state', '1')->count();
				$screenInactive= \App\Models\Screen::where('state', '0')->count();
				return view('client.index',compact('screenActive' ,'screenInactive','user','screens','events','modelComputer','modelStore'));
			}
			else if(Auth::user()->hasRole('Supervisor')){

			}
			else if(Auth::user()->hasRole('Diseño')){

			}

			return view('principal.sinrole');

    }
}
