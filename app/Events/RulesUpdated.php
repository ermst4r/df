<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RulesUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel_feed_id;
    public $channel_type_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel_feed_id,$channel_type_id)
    {
        //
        $this->channel_feed_id = $channel_feed_id;
        $this->channel_type_id = $channel_type_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return  ['rules_updated'];
    }


}
