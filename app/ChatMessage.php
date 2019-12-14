<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChatMessage
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChatMessage whereUserId($value)
 * @mixin \Eloquent
 */
class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    protected $fillable = [
        'user_id'
        ,'text'
        ,'seen'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
