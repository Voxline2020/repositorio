<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class ActivateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activate:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activa eventos segun la fecha de programacion';

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
			$today=date('Y-m-d H:i:s');
			$InactiveEvents = Event::where('state',0)
			->whereDate('initdate', '<=', $today)
			->whereDate('enddate', '>=', $today)
			->get();
			foreach($InactiveEvents AS $Inactive){
				$Inactive->state = 1;
				$Inactive->save();
			}
    }
}
