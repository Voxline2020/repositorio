<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon as Carbon;
use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Auth;
use Mail;

class NotifyOneDayLeft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:onedayleft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Funcion que envia a los usuarios de cada empresa registrada un email con los eventos que esten a 1 dia de expirar';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
					}
				}
			}
		}
}
