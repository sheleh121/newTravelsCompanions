<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $api_token
 * @property int $locked
 * @property string $phone
 * @property string $key_sms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ChatRoom[] $chatRooms
 * @property-read int|null $chat_rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notice[] $notices
 * @property-read int|null $notices_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $travels
 * @property-read int|null $travels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $travelsAuthor
 * @property-read int|null $travels_author_count
 * @property-read \App\UserInfo $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereKeySms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','key_sms', 'phone', 'locked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'locked'
    ];

    public function travelsAuthor()
    {
        return $this->hasMany('App\Travel');
    }

    public function notices()
    {
        return $this->hasMany('App\Notice');
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class)->withTimestamps();
    }

    public function travels()
    {
        return $this->belongsToMany('App\Travel');
    }

    public function userInfo()
    {
        return $this->belongsTo('App\UserInfo', 'id', 'user_id');
    }

    public function findForPassport($identifier) {
        return $this->where('phone', $identifier)->first();
    }
}
