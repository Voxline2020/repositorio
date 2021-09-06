<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function logout()
	{
		Auth::logout();
		return redirect()->intended('/login');
	}


	public function authenticate(Request $request)
	{
		//! Validacion de campos
		$validator = Validator::make($request->all(), [
			'email' => 'required|email|max:50',
			'password' => 'required|max:30',
		]);

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}
		//! Validacion de usuario

		$credentials = $request->only('email', 'password');
		if (Auth::attempt($credentials)) {
			$user = Auth::user();
			// if($user->hasRole('Administrador')){
			// 	return redirect()->intended('/clients');
			// }
			// else if($user->hasRole('Cliente')){
			// 	return redirect()->intended('/clients');
			// }
			// else if($user->hasRole('Cliente')){

			// }
			// else{
			// 	return redirect()->intended('/clientes');
			// }

			return redirect()->intended('/');
		}

		$validator->after(function ($validator) use ($request) {
				$validator->errors()->add('auth', 'Usuario no existe o la contraseña es erronea');
		});

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		// $errors = $validator->errors();

	}
}
