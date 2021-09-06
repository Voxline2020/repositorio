<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon as Carbon;
use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Auth;
use Mail;

class EmailController extends Controller
{
	public function NotifyOneDayLeft()
	{
		//listamos todas las empresas
		$companies = Company::all();
		//recorremos las empresas
		foreach($companies as $company){
			//listamos a todos los usuario de la empresa
			$users = User::where("company_id",$company->id)->get();
			//listamos a todos los administradores
			$admins = User::wherehas('roles', function($query){
				$query->where('id', 1);
			})->get();
			//listamos todos los eventos de la empresa
			$events = Event::where('company_id',$company->id)
			->where('enddate', '>=',Carbon::now()->add(+1, 'day')->format('Y-m-d 00:00:00'))
			->where('enddate', '<=',Carbon::now()->add(+1, 'day')->format('Y-m-d 23:59:00'))
			->get();
			// comprobamos si hay eventos proximos a expirar
			if($events->count()!=0){
				//recorremos los usuarios de la empresa
				foreach($users as $user){
					//seteamos los datos del emisor,receptor y asunto
					$from = 'voxline.notification@gmail.com';
					$fromName = 'Notificaciones VxCMS';
					$subject = 'Notificacion de expiración de eventos.';
					$for = $user->email;
					$forName = $user->name.' '.$user->lastname;
					//enviar correo
					Mail::send('layouts.notifyEventOneDayLeft',['events' => $events,'user' => $user],
					function($message)
					use($subject,$for,$forName,$from,$fromName)
					{
						$message->from($from,$fromName );
						$message->to($for,$forName);
						$message->subject($subject);
						$message->priority(3);
					});
				}
				//recorremos los admins
				foreach($admins as $admin){
					//seteamos los datos del emisor,receptor y asunto
					$from = 'notificaciones@voxline.cl';
					$fromName = 'Notificaciones VxCMS';
					$subject = 'Notificación de expiración de eventos para '.$company->name.'.';
					$for = $admin->email;
					$forName = $admin->name.' '.$admin->lastname;
					//enviar correo
					Mail::send('layouts.notifyEventOneDayLeft',['events' => $events,'user' => $admin],
					function($message)
					use($subject,$for,$forName,$from,$fromName)
					{
						$message->from($from,$fromName );
						$message->to($for,$forName);
						$message->subject($subject);
						$message->priority(3);
					});
					return view('layouts.notifyEventOneDayLeft')->with('events',$events)->with('user',$admin);
				}
			}
		}
	}
	public function NotifyCreateUser(Request $request)
		{
			//seteamos emisor,receptor y asunto.
			$from = 'voxline.notification@gmail.com';
			$fromName = 'Notificaciones VxCMS';
			$subject = 'Creación de usuario exitoso.';
			$for = $request->email;
			$forName = ''.$request->name.' '.$request->lastname.'';
			$request->merge(['password' => $request['password']]);
			$data = $request->all();
			//enviamos el mail
			Mail::send('layouts.notifycreateuser',$data,
			function($message)
			use($subject,$for,$forName,$from,$fromName)
			{
				$message->from($from,$fromName );
				$message->to($for, $forName);
				$message->subject($subject);
				$message->priority(3);
			});
		}
}
