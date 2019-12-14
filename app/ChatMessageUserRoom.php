<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\ChatMessageUserRoom
 *
 * @property int $id
 * @property int $chat_message_id
 * @property int $user_id
 * @property int $chat_room_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $seen
 * @property-read \App\ChatMessage $message
 * @property-read \App\ChatRoom $room
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereChatMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereChatRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessageUserRoom whereUserId($value)
 * @mixin \Eloquent
 */
class ChatMessageUserRoom extends Model
{
    protected $fillable = [
        'chat_message_id'
        ,'chat_room_id'
        ,'user_id'
        ,'seen'
    ];

    protected $table = 'chat_message_user_room';

    public function user()
    {
        return $this->hasOne('App\User');
    }
    public function room()
    {
        return $this->hasOne('App\ChatRoom');
    }
    public function message()
    {
        return $this->belongsTo('App\ChatMessage', 'chat_message_id', 'id');
    }

    public static function getMessages($user_id, $room_id)
    {

        return DB::table('chat_message_user_room')
            ->leftjoin('chat_messages', 'chat_message_user_room.chat_message_id', '=', 'chat_messages.id')
            ->leftjoin('users_info', 'chat_messages.user_id', '=', 'users_info.user_id')
            ->select('chat_message_user_room.id as id'
                ,'chat_message_user_room.chat_message_id as message_id'
                ,'chat_message_user_room.seen as seen'
                ,'chat_messages.text as text'
                , 'chat_messages.user_id as user_id'
                , DB::raw('CONCAT(users_info.name
                    , " "   
                    , IFNULL(users_info.surname, " ")
                    ) AS user_name
                    , DATE_FORMAT(chat_messages.created_at, "%d/%m/%Y %H:%i")  as created_at
                ')
            )
            ->where([
                'chat_message_user_room.user_id' => $user_id,
                'chat_message_user_room.chat_room_id' => $room_id
            ])
            ->orderBy('chat_messages.created_at', 'desc')
            ->simplePaginate(20);
    }
}
