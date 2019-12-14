<?php

namespace App\Events\Travels;


use App\Notice;
use App\Travel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelNoticeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $description;
    public $travel_name;
    public $travel_id;
    public $user_id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($description, $user_id, $travel_id)
    {
        $notice = Notice::create([
            'description' => $description,
            'user_id' => $user_id,
            'travel_id' => $travel_id
        ]);
        $travel = Travel::find($travel_id);

        $this->id = $notice->id;
        $this->description = $notice->description;
        $this->travel_name = $travel->name;
        $this->travel_id = $notice->travel_id;
        $this->user_id = $notice->user_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notice.' . $this->user_id);
    }
}
