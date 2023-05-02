<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\DownloadCsvImages;
use App\Console\Commands\ImageConvert;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       // $schedule->call(function () { echo "hello vishal"; })->everyMinute()->emailoutputto('gopinathjivishal@gmail.com');
        $schedule->command(DownloadCsvImages::class)->everyMinute(); // ->emailoutputto('gopinathjivishal@gmail.com');
        $schedule->command(ImageConvert::class)->everyMinute();
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
