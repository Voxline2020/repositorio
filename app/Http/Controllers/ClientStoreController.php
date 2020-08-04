<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str as Str;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Content;
use App\Models\Company;
use App\Models\Computer;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Flash;
use Response;

class ClientStoreController extends Controller
{
	private $eventRepository;

	public function __construct(EventRepository $eventRepo)
	{
		$this->middleware('auth');
		$this->eventRepository = $eventRepo;
	}
	
    public function index(Request $request)
	{

		$stores = Store::where('company_id', Auth::user()->company_id)->get();

		 $company_id = Auth::user()->company_id;
		 
		 //$events = Event::where("company_id","=",$company_id)->where("state","=",1)->get();
		 
		$events = DB::select( DB::raw("SELECT events.* FROM events WHERE  events.company_id = ".$company_id." && events.deleted_at IS NULL ORDER BY 'NAME' ASC ") );
		 
		
		return view('client.clientStoresScreens')
		->with('stores',$stores)
		->with('events',$events);		

	}
 

	//Funcion ajax por post cuando se hace clic en una sucursal
	public function funcionajax(Request $request)
	{
		 //sacamos la informacion del request
		 $data = $request->getContent();
		 					 
		 //Rescatamos la id de sucursal enviado por ajax
		 $idSucursal = $request->idStore;

		 //rescatamos la tienda asociado al id extraido
		 $store = Store::where('id', $idSucursal)->first();

		 //asignamos el nombre de la sucursal
		 $nombreSucrusal = $store->name;

		 //Busco los computadores por sucursal
		 $computadores = Computer::where('store_id', $idSucursal)->get();
		 
		 $company_id = Auth::user()->company_id;

		 //devices por computador
		 $devices = DB::select( DB::raw("SELECT devices.* FROM computers , devices WHERE computers.store_id = '$idSucursal' && devices.computer_id  = computers.id && devices.state > 0") );
		 
		//eventos en la lista del modal
		$events = DB::select( DB::raw("SELECT events.* FROM events WHERE  company_id = '$company_id' && deleted_at IS NULL ") );

		

		 //id de la compañia del usuario en sesion
		 //$company_id = Auth::user()->company_id;

		 //EVENTS
		 //eventos por compañia
		 //$events = Event::where('company_id' , Auth::user()->company_id)->get();		 

		 //generando el array json
		 //$jsondata['data'] = $data;
		 $jsondata['sucess'] = "true";
		 //$jsondata['screens'] = $screens;
		 $jsondata['devices'] = $devices;
		 $jsondata['sucursal'] = $nombreSucrusal;
		 //$jsondata['company_id'] = $company_id;
		 $jsondata['events'] = $events;
		 //$jsondata['eventsAssigns'] = $eventAssigns;
		 //$jsondata['duracion'] = $totalduration;
		 
		//retornamos la informacion en un json  con un echo
		 echo json_encode($jsondata);		 
		 exit();
		
	}

	
	//Funcion para asignar un contenido a una pantalla crea el evento
	//luego crea el contenido para finalmente assignar el evento con su contenido al device
	public function guardarAsignar(Request $request)
	{
		/*$today = Carbon::now()->toDateTimeString();	
		if($request->initdate < $today)
		{
			dd('inactivo');
		}else
		{
			dd('activo');
		}
		dd($request->initdate);*/

		//comprovamos que el nombre del evento no este vacio
		if($request->event_name != '')
		{
			//comprovamos que las fechas no esten vacias
			if($request->initdate != '' && $request->enddate != '' )
			{

				//Compravamos el largo del string fecha
				$digitos = strlen($request->initdate);
				
				if($digitos == 19)
				{
					//formateamos la fecha para guardarla en la base de datos 
					$initdate = Carbon::createFromFormat('Y-m-d H:i:s', $request->initdate)->toDateTimeString();	
					
				}elseif ($digitos == 16){
					//formateamos la fecha para guardarla en la base de datos 
					$initdate = Carbon::createFromFormat('d/m/Y H:i', $request->initdate)->toDateTimeString();	;
					
				}elseif ($digitos == 15){
					//formateamos la fecha para guardarla en la base de datos 
					$initdate = Carbon::createFromFormat('d/m/Y H:i', $request->initdate)->toDateTimeString();			
					
				}
				else{
					return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Ingrese un formato valido de fecha ');  
				} 

				//comprovamos cuantos digitos trae la fecha
				$digitos = strlen($request->enddate);
				
				if($digitos == 19)
				{
					//formateamos la fecha para guardarla en la base de datos 
					$enddate = Carbon::createFromFormat('Y-m-d H:i:s', $request->enddate)->toDateTimeString();	
					
				}elseif ($digitos == 16){
					//formateamos la fecha para guardarla en la base de datos 
					$enddate = Carbon::createFromFormat('d/m/Y H:i', $request->enddate)->toDateTimeString();	
					
				}elseif ($digitos == 15){
					//formateamos la fecha para guardarla en la base de datos 
					$enddate = Carbon::createFromFormat('d/m/Y H:i', $request->enddate)->toDateTimeString();								
				}
				else{
					return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Ingrese un formato valido de fecha ');  
				} 
				
				//comprovamos que la fecha de inicio sea menor que la de termino
				if($initdate < $enddate){

					//comprovamos se este cargando un archivo
					$file = $request->contenido;
					if(!(is_null($file)))
					{	
						
						$filetype = $file->extension();
						
						//SOLO PERMITE MP4
						if($filetype == 'mp4')
						{
							
							//Extraemos la informacion del video subido
							//nombre original
							//$nombreOriginal = str_replace(' ','-',$file->getClientOriginalName());	
							//$nombreOriginal = strtolower($nombreOriginal);

							//tamaño
							//$size = $file->getClientSize();
							//mime
							//$mime = $file->getClientmimeType();							
							//creamos una nueva instancia de la libreria getID3
							////Analizar Video
							$getID3 = new \getID3;
							$fileX = $getID3->analyze($file);
							$filetype = $file->getClientOriginalExtension();
							$mime = $file->getClientMimeType();
							$user_id = Auth::user()->id;
							$size = $file->getSize();
							$width = $fileX['video']['resolution_x'];
							$height = $fileX['video']['resolution_y'];
							//$duration = EventController::formatDuration($fileX['playtime_string']);
							$duracion = $fileX['playtime_seconds'];
							//$duracion en hh:mm:ss
							$duration = gmdate("H:i:s", $duracion);

							//Nombre archivo
							
							//$name = Str::slug($event->slug . '_' . $width . 'x' . $height);
							//$original_name = Str::slug($event->slug . '_' . $width . 'x' . $height);
							//$slug = Str::slug($name);

							//Guardar archivos
							//$path = Storage::disk('videos')->put($event->slug . "/" . $name, $file);

							//$getID3 = new \getID3;				
							//analizamos el video utilizando la libreria
							//$file2 = $getID3->analyze($file);

							//obtenemos la informacion del analizis del archivo suvido											
							//ancho								
							//$width = $file2['video']["resolution_x"];
							//alto
							//$height = $file2['video']["resolution_y"];

							//duracion en segundos
							//$duracion = $file2['playtime_seconds'];
							//duracion en hh:mm:ss
							//$duracion = gmdate("H:i:s", $duracion);
							//id usuario
							//$user_id = Auth::user()->id;

							//nombre nuevo
							//$name = $nombreOriginal;
							
							//creamos un nuevo objeto contenido y lo llenamos
							$content = new Content;								
							//$content->name = $name;
							//$content->original_name = $original_name;
							//$slug = str_replace(' ', '-' , $name).'-'.$width.'x'.$height;			
							//$content->slug = $slug;
							$content->filetype = $filetype;							
							$content->user_id = $user_id;
							$content->size = $size;
							$content->width = $width;
							$content->height = $height;
							$content->duration = $duration;
							//$content->event_id = $idevent;		
							//
							$content->mime = $mime;	

							//comprovamos que ningun campo del contenido este vacio exepto location que se obtiene al guardar el video
							if($content->filetype != '' && $content->user_id != '' && $content->size != '' && $content->width != '' && $content->height != '' && $content->duration != '' && $content->mime != '')
								{
									//extraemos los datos del request
									$device_id = $request->device_id;
									$device_width = $request->device_width;
									$device_height = $request->device_height;
									
									//comprobamos que tenemos el device_id , el alto y ancho
									if($device_id != '' && $device_width != '' && $device_height != '')
									{
										//comprovamos las dimenciones de la pantalla con el video
										//si las dimenciones del contenido son mayores que las dimensiones de la pantalla
										if($content->height >= $device_height && $content->width >= $device_width)
										{											
											$company_id = Auth::user()->company_id;					
											//Creamos un nuevo evento
											$evento = new Event;

											//cargamos los datos al evento
											$evento->name = strtolower($request->event_name);
											$evento->initdate = $initdate;
											$evento->enddate = $enddate ;
											$today = Carbon::now()->toDateTimeString();	
											if($initdate > $today)
											{												
												$evento->state = 0;									
											}else
											{												
												$evento->state = 1;								
											}
											
											
											$event_slug = str_replace(' ', '-', $request->event_name);
											$evento->slug = $event_slug;
											$evento->company_id = $company_id;

											//creamos los nombre del contenido en base al evento
											$name = Str::slug($evento->slug . '_' . $width . 'x' . $height);
											$original_name = Str::slug($evento->slug . '_' . $width . 'x' . $height);
											$slug = Str::slug($name);

											$content->name = $name;
											$content->original_name = $original_name;
											$content->slug = $slug;

											
											//comprovamos los datos del objeto evento
											if($evento->name != '' && $evento->initdate != '' && $evento->enddate != '' &&  $evento->slug != '' && $evento->company_id != '' ){

												//comprovamos que el archivo se suba al servidor antes de crear el nuevo evento al cual estara asociado el contenido 
												//comprovamos que exista el contenido
												if($request->file('contenido') == '')
												{
													return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No existe el video ???  ');  
												}

												//cargamos el video al servido y obtenemos la location
												//guardamos en una carpeta con el nombre del evento
												//$url = str_replace(' ' , '-', $request->event_name);
												//$url = $url.'/'.$nombreOriginal;
												//$url = $evento->slug . "/" . $name;
												//$url = strtolower($url);
												//comprobar contenido de = resolucion
												//$location = $request->file('contenido')->store($url);
												$location = Storage::disk('videos')->put($evento->slug . "/" . $name, $file);
												if($location == '')
												{
													return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No se pudo subir el vieo al servidor  ');  
												}
												
												//Extraemos el event id si existe?
												$event_id = $request->event_id;
												$guardar = '';
												
												//si no existe el event_id guardamos un nuevo evento
												if(is_null($event_id))
												{
													//guardamos el nuevo evento
													
													$guardar = $evento->save();

													//si el evento se guardo
													if($guardar == 'true')
													{
														//capturamos el ultimo evento guardado
														$lastevent = Event::where('company_id' , $company_id)->latest('created_at')->first();
														$event_id = $lastevent->id;			
														
													}else
													{
														return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Evento no guardado  ');  

													}
													
													
												}else
												{
													//si el evento ya existia 
													//asignamos el id recivido al evento			$evento->id = $event_id;
													
												}												
												if($event_id != '')								
													{														
														//terminamos cargamos event_id al objeto contenido
														$content->event_id = $event_id;

													
        												if($location != '')
        												{
        													//le cargamos location al objeto contenido 
        													$content->location = $location;
        													if($content->save())
        													{
        														//capturamos el ultimo evento guardado
																$last_content = Content::where('event_id' , $event_id)->orderby('id','DESC')->first();	
																$content_id = $last_content->id;	
																
																//Creamos un nuevo objeto event assignation y lo llenamos
        														$event_assignation = new EventAssignation;
        														$event_assignation->content_id = $content_id;
        														$event_assignation->device_id = $device_id;
        														$event_assignation->user_id = $user_id;
        														$today = Carbon::now()->toDateTimeString();	
																if($initdate > $today)
																{										$event_assignation->state = 0;	
																}else
																{									
																	$event_assignation->state = 1;				
																}
																$count_assigns = EventAssignation::where('device_id',$device_id)->where('state',1)->count()+1;
        														$event_assignation->order = $count_assigns; //order?

        														
        														if($event_assignation->save())
        														{        								 //actualizamos la version													
				        											//primero identificamos la pantalla
				        											$device = Device::find($device_id);
																	# Los nuevos datos
																	$device->version = $device->version+1;
																	# Y guardamos ;)
																	$device->save();								
        															return redirect('clientStore')->with('success', 'Contenido asignado');  
        														}else{
        															return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No se pudo asignar el evento a la pantalla');  
        														}//fin guardar evento asignado
        														
        														
        													}else
        													{
        														return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No se pudo guardar el contenido  ');  
        													}//fin guardar contenido en la base de datos        													
        												}else
        												{
        													return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *no se pudo suvir el video al servidor');  
        												}//Fin suvir video al servidor
														
													}else
													{
														return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No se encontro el ultimo evento guardado');  
													}//fin comprovar ultimo evento guardado
											
											}else
											{
												return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Hubo un error al extraer la informacion el video  ');  
											}//fin comprovar datos evento
										}else
										{
											return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No se permite cargar un video de menor resolucion que la pantalla  ');  
										}//fin comprovar tamaño video >= tamaño pantalla
									}else
									{
										return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *No selecciono una pantalla como llego hasta aca?  ');  
									}//fin comprovar informacion del device
									
									
								}else
								{
									return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Error al extraer datos del archivo');  
								}	//final revision de contenido						

						}else
						{
							return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Debe seleccionar un archvio de video .MP4');  
						}//fin revision de formato
					}else
					{
						return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *Debe seleccionar un archvio');  
					}//fin comprobar si existe archivo $file 			
							
				}else{
					return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *La fecha de  inicio debe ser menor a la fecha de termino');  
				}//fin initdate < enddate					
				
				
			}else
			{
				return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *El campo fecha inicio y fecha son obligatorios');  				
			} //fin initdate and enddate != ''
		
		}else
		{
			 return redirect()->back()->with('error', 'ERROR: No se pudo asignar el contenido <br> *El campo nombre es obligatorio');  
		}; //fin comprobar nombre
		//extraccion y fomateo de fechas
		
		

		
	} //Final guardar asignar


}