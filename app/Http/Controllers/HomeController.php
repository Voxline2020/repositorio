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
				return redirect(route('companies.index'));
			}
			else if(Auth::user()->hasRole('Cliente')){
				return redirect(route('clients.index'));
			}
			else if(Auth::user()->hasRole('Supervisor')){
                return redirect(route('clients.index'));
			}
			else if(Auth::user()->hasRole('Diseño')){

			}

			return view('principal.sinrole');

    }
}
