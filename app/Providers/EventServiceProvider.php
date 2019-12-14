<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Chat\RoomSubscribeEvent' => [
            'App\Listeners\Chat\RoomSubscribeListener',
        ],
        'App\Events\Chat\ChatMessageEvent' => [
            'App\Listeners\Chat\ChatMessageListener',
        ],
    ];

    /**
     * Register any travels for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
