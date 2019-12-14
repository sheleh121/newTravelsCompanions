<?php

namespace App\Events\Travels;

use App\TravelUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelInviteEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $travel_user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($travel_user_id)
    {
        $this->travel_user = TravelUser::with(
            'claim'
            ,'travel.city'
            , 'travel.country'
            , 'travel.region'
            , 'travel.type'
            , 'travel.category'
            ,'travel.author'
        )->find($travel_user_id);

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user_travels.' . $this->travel_user['user_id']);
    }
}
