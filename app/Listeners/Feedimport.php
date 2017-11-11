<?php

namespace App\Listeners;

use App\DfCore\DfBs\Log\LoggerFacade;
use App\Events\FeedImported;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Feedimport implements ShouldQueue
{

    use InteractsWithQueue;


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
    public function handle(FeedImported $event)
    {

        return $event->feed_id;
    }




}
