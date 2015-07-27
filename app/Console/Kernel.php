<?php

namespace CityHapps\Console;

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
        \CityHapps\Console\Commands\Inspire::class,
        \CityHapps\Console\Commands\StoreEventsCommand::class,
        \CityHapps\Console\Commands\ConvertEventsCommand::class,
        \CityHapps\Console\Commands\ClearApi::class,
        \CityHapps\Console\Commands\PullApi::class,
        \CityHapps\Console\Commands\LoadApiData::class,
        \CityHapps\Console\Commands\StaleApiData::class,
        \CityHapps\Console\Commands\PullApiVenues::class,
        \CityHapps\Console\Commands\LoadApiVenuesData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
