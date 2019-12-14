<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * App\TravelUser
 *
 * @property int $id
 * @property int $travel_id
 * @property int $user_id
 * @property int|null $claim
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Travel $travel
 * @property-read \App\UserInfo $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereClaim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelUser whereUserId($value)
 * @mixin \Eloquent
 */
class TravelUser extends Model
{
	protected $table = 'travel_user';

    protected $fillable = [
		'claim'
        ,'user_id'
        ,'travel_id'

    ];
    public function travel()
    {
        return $this->belongsTo('App\Travel');
    }

    public function userInfo(){
        return $this->belongsTo('App\UserInfo', 'user_id', 'user_id');
    }

    public function claim()
    {
        return $this->belongsTo('App\Claim', 'claim');
    }

}
