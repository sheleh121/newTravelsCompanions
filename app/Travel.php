<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Travel
 *
 * @property int $id
 * @property int $user_id
 * @property int $travel_type_id
 * @property int $travel_category_id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property string $name
 * @property string $description
 * @property int $decision
 * @property int|null $success
 * @property string $date_begin
 * @property string $date_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $room_id
 * @property int $commercial
 * @property-read \App\UserInfo $author
 * @property-read \App\TravelCategory $category
 * @property-read \App\LocationCity $city
 * @property-read \App\LocationCountry $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $image
 * @property-read int|null $image_count
 * @property-read \App\LocationRegion $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TravelUser[] $travelUsers
 * @property-read int|null $travel_users_count
 * @property-read \App\TravelType $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereCommercial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereDateBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereDecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereTravelCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereTravelTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Travel whereUserId($value)
 * @mixin \Eloquent
 */
class Travel extends Model
{

    //
    protected $table = 'travels';

    protected $fillable = [
        'name'
        ,'description'
        ,'travel_category_id'
        ,'country_id'
        ,'region_id'
        ,'city_id'
        ,'user_id'
        ,'travel_type_id'
        ,'date_end'
        ,'date_begin'
        ,'decision'
        ,'room_id'
        ,'commercial'
    ];

    public function city()
    {
        return $this->belongsTo('App\LocationCity');
    }
    public function country()
    {
        return $this->belongsTo('App\LocationCountry');
    }
    public function region()
    {
        return $this->belongsTo('App\LocationRegion');
    }
    public function type()
    {
        return $this->belongsTo('App\TravelType', 'travel_type_id');
    }

    public function category()
    {
        return $this->belongsTo('App\TravelCategory', 'travel_category_id');
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function author()
    {
        return $this->belongsTo('App\UserInfo', 'user_id');
    }


    public function image()
    {
        return $this->hasMany('App\Image');
    }
    public function travelUsers()
    {
        return $this->hasMany('App\TravelUser');
    }

}
