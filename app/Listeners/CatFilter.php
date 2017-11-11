<?php

namespace App\Listeners;

use App\Events\CatFilterProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CatFilter implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CatFilterProcessed  $event
     * @return string
     */
    public function handle(CatFilterProcessed $event)
    {
        return $event->feed_id;
    }




}
