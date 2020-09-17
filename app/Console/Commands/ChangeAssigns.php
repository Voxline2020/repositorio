<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventAssignation;
use App\Models\Screen;
use App\Models\Device;

class ChangeAssigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:assigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa los eventos para desctivar/activar las asignaciones.';

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
			$events = Event::all();
			$eventAssigns = EventAssignation::all();
			foreach ($events as $event) {
				foreach($event->contents AS $content){
					foreach ($eventAssigns as $assignation) {
						if($assignation->content_id==$content->id){
							if($assignation->state!=$content->event->state){
								$assignation->state = $content->event->state;
                                $assignation->save();
                                 //cambiar screen por device 
								$device = Device::find($assignation->device_id);
								$device->version = $device->version+1;
								$device->save();
							}
						}
					}
				}
			}
    }
}
