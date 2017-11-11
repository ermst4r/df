<?php

namespace App\Console;

use App\Console\Commands\DfJobScheduler;
use App\Console\Commands\TestingJobs\Import;
use App\Console\Commands\TestingJobs\PushToAdwords;
use App\Console\Commands\CreateAdwordsCountries;
use App\Console\Commands\GetChannels;
use App\Console\Commands\TestingJobs\InsertTmpRule;
use App\Console\Commands\SendToAdwords;
use App\Console\Commands\TestingJobs\RefreshCounters;
use App\Console\Commands\TestingJobs\UpdateBol;
use App\Console\Commands\TestingJobs\UpdateRuleProducts;
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
        UpdateRuleProducts::class,
        GetChannels::class,
        PushToAdwords::class,
        InsertTmpRule::class,
        SendToAdwords::class,
        CreateAdwordsCountries::class,
        RefreshCounters::class,
        DfJobScheduler::class,
        Import::class,
        UpdateBol::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('dfjobscheduler')
                  ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }


}
