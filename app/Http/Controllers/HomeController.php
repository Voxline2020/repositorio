<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Company;
use Doctrine\Common\Cache\RedisCache;
use Flash;


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

    public function dash(Request $request)
    {
			$error = $request->session()->get('error');
			if(Auth::user()->hasRole('Administrador')){
				return redirect(route('companies.index'))->with('error',$error);
			}
			if(Auth::user()->hasRole('Terreno')){
				return redirect(route('companies.terreno.index'))->with('error',$error);
			}
			else if(Auth::user()->hasRole('Cliente')){
				return redirect(route('clients.index'))->with('error',$error);
			}
			else if(Auth::user()->hasRole('Supervisor')){

        return redirect(route('clients.index'))->with('error',$error);
			}
			else if(Auth::user()->hasRole('Diseño')){
				return redirect(route('clients.index'))->with('error',$error);
			}

			return view('principal.sinrole');

    }
}
