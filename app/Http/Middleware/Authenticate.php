<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Flash;
use Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
		 */




    protected function redirectTo($request)
    {
			if (!$request->expectsJson()) {
				if($request->getPathInfo()!='/'){
					Flash::error('Debes iniciar sesión para acceder a la aplicación.');
				}
				return route('login');
			}
    }
}
