<?php

namespace App\Listeners\Chat;

use App\Events\Chat\ChatMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChatMessageListener
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
     * @param  ChatMessageEvent  $event
     * @return void
     */
    public function handle(ChatMessageEvent $event)
    {
        //
    }
}
