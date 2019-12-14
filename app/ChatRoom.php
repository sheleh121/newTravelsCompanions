<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChatRoom
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $private
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ChatMessageUserRoom[] $messageUserRoom
 * @property-read int|null $message_user_room_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatRoom whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatRoom extends Model
{
    protected $fillable = [
        'name', 'private'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function messageUserRoom()
    {
        return $this->hasMany('App\ChatMessageUserRoom');
    }

}
