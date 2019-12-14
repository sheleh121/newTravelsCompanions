<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 13.01.19
 * Time: 22:52
 */

namespace App\Http\Controllers\Chat;

use App\ChatMessage;
use App\ChatMessageUserRoom;
use App\ChatRoom;
use App\Events\Chat\RoomSubscribeEvent;
use App\Http\Controllers\Controller;

use App\Events\Chat\ChatMessageEvent;
use Illuminate\Http\Request;
use Auth;



class ChatMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send(Request $request)
    {
        $message = ChatMessage::create([
            'text' => $request['text'],
            'user_id' => Auth::user()->id
        ])->toArray();
        foreach (ChatRoom::find($request['chat_room_id'])->users as $user){
            $seen = false;
            if ($user->id == Auth::user()->id)
                $seen = true;
            else RoomSubscribeEvent::dispatch($request['chat_room_id'], $user->id);

            ChatMessageUserRoom::create([
                'chat_message_id' => $message['id'],
                'user_id' => $user['id'],
                'chat_room_id' => $request['chat_room_id'],
                'seen' => $seen
            ]);

        }

        //!!!!!!!!!!!!!!!!!Проверить вывод ФИО !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $message['user_name'] = Auth::user()->name;
        $message['chat_room_id'] = $request['chat_room_id'];
        $message['seen'] = false;

        ChatMessageEvent::dispatch($message);

    }

    public static function noSeen() {
        return ChatMessageUserRoom::where('user_id', Auth::user()->id)
            ->where('seen', false)
            ->count();
    }

    public function seen(Request $request){
        $messages = ChatMessageUserRoom::where('user_id', Auth::user()->id)
            ->whereIn('chat_message_id', $request['messages_id'])
            ->get();
        foreach ($messages as $message){
            $message->seen = true;
            $message->save();
        }
        return $messages;
    }


    public static function get(Request $request)
    {

        $user_id = Auth::user()->id;
        $room_id = $request['room_id'];
        //Залипуха.
        return ChatMessageUserRoom::getMessages($user_id, $room_id);
    }

}
