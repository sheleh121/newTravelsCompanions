<?php

namespace App\Listeners\Chat;

use App\Events\Chat\RoomSubscribeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoomSubscribeListener
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
     * @param  RoomSubscribeEvent  $event
     * @return void
     */
    public function handle(RoomSubscribeEvent $event)
    {
        //
    }
}
