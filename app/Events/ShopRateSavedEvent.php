<?php

namespace App\Events;

use App\Models\ShopRate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShopRateSavedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $shop_rate;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ShopRate $shop_rate)
    {
        $this->shop_rate = $shop_rate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
