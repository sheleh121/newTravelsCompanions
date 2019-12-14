<?php

namespace App\Http\Controllers\Chat;

use App\ChatMessageUserRoom;
use App\ChatRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class ChatRoomController extends Controller
{
    public function getForUser(){

        $rooms = Auth::user()->chatRooms()->orderByDesc('updated_at')
            ->simplePaginate(20);
        foreach ($rooms as $room){
            $room['no_seen'] = ChatMessageUserRoom::where('user_id', Auth::user()->id)
                ->where('chat_room_id', $room->id)
                ->where('seen', false)
                ->count();
            if ($room['private'] == 1){
                foreach ($room->users as $user){
                    if ($user['id'] != Auth::user()->id)
                        $room['name'] = $user['name'];
                }
            }
        }
        return $rooms;
    }


    public function dialogNew(Request $request){
        $user_current = Auth::user();
        $user_invited = User::find($request['user_invited']);

        foreach($user_current->chatRooms()->where('private', true)->get() as $user_current_room ){
            foreach($user_invited->chatRooms()->where('private', true)->get() as $user_invited_room ){
                if ($user_current_room['id'] = $user_invited_room['id']) $room_id = $user_current_room->id;
            };
        };
        if (!isset($room_id)){
            $room_id = ChatRoom::create(['private' => true])->id;
            $user_current->chatRooms()->attach($room_id);
            $user_invited->chatRooms()->attach($room_id);
        }
        return $room_id;
    }

    public static function meetingNew ($name) {
        $room_id = ChatRoom::create(['private' => false, 'name' => $name])->id;
        Auth::user()->chatRooms()->attach($room_id);
        return $room_id;
    }

}


