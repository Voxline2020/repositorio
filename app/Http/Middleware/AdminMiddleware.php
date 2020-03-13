<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Flash;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

				if(auth()->check() && Auth::user()->hasRole('Administrador')){
					return $next($request);
				}else	if(auth()->check() && Auth::user()->hasRole('Terreno')){
					return $next($request);
				}else{
				 	return redirect('/')->with('error', 'Debes ser administrador para acceder a esta sección.');
				}
    }
}
