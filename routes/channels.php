<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    return $user->chatRooms->contains($room_id);
});

Broadcast::channel('user_travels.{room_id}', function ($user, $room_id) {
    if ($user->id == $room_id) {
        return true;
    }
});

Broadcast::channel('notice.{room_id}', function ($user, $room_id) {
    if ($user->id == $room_id) {
        return true;
    }
});

Broadcast::channel('notice-message.{user_id}', function ($user, $user_id) {
    if ($user->id == $user_id) {
        return true;
    }
});
