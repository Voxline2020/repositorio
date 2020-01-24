<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class DesactivateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desactivate:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactiva eventos segun la fecha de programacion';

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
			//
			$today = date('Y-m-d H:i:s');
			$ActiveEvents = Event::where('state',1)
			->whereDate('enddate', '<=', $today)
			->get();
			foreach($ActiveEvents AS $Active){
				$Active->state = 0;
				$Active->save();
				foreach ($Active->contents as $content) {
					foreach ($Active->eventAssignations as $assignation) {
						$assignation->state = 0;
						$assgiantion->save();
					}
				}
			}
    }
}
