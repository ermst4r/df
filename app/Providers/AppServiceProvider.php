<?php

namespace App\Providers;

use App\DfCore\DfBs\Log\LoggerFacade;
use Illuminate\Queue\Events\JobFailed;
use Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
