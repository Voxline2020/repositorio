<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Flash;

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
						Flash::error('Debes iniciar sesión para acceder a la aplicación.');
            return route('login');
        }
    }
}
