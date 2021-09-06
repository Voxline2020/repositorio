<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
				//
				'App\Console\Commands\ActivateEvents',
				'App\Console\Commands\DesactivateEvents',
				'App\Console\Commands\ChangeAssigns',
				'App\Console\Commands\NotifyOneDayLeft',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
			$schedule->command('activate:events')->everyMinute();
			$schedule->command('desactivate:events')->everyMinute();
			$schedule->command('change:assigns')->everyMinute();
			$schedule->command('notify:onedayleft')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
		}

}
