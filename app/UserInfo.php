<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\UserInfo
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $surname
 * @property string $name
 * @property string $patronymic
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property int $karma
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $image_id
 * @property-read \App\Image $avatar
 * @property-read \App\LocationCity $city
 * @property-read \App\LocationCountry $country
 * @property-read \App\LocationRegion $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $travels
 * @property-read int|null $travels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TravelUser[] $travelsUser
 * @property-read int|null $travels_user_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereKarma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInfo whereUserId($value)
 * @mixin \Eloquent
 */
class UserInfo extends Model
{
    //

    protected $table = 'users_info';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'id', 'user_id', 'name', 'surname', 'patronymic', 'city_id', 'country_id', 'region_id', 'karma', 'image_id'
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

    public function travels()
    {
        return $this->belongsToMany('App\Travel', 'travel_user', 'user_id', 'user_id');
    }

    public function travelsUser()
    {
        return $this->hasMany('App\TravelUser', 'user_id', 'user_id');
    }

    public function avatar()
    {
        return $this->belongsTo('App\Image');
    }

    public static function GetUsers($where)
    {
        return DB::table('users_info')
            ->leftjoin('location_cities', 'users_info.city_id', '=', 'location_cities.id')
            ->leftjoin('location_countries', 'users_info.country_id', '=', 'location_countries.id')
            ->leftjoin('location_region', 'users_info.region_id', '=', 'location_region.id')
            ->select( 'users_info.user_id as id'
                , DB::raw('CONCAT(IFNULL(users_info.surname, "")
                    , " "
                    , IFNULL(users_info.name, "")
                    , " "
                    , IFNULL(users_info.patronymic, "")) AS user_name')
                , 'users_info.karma as karma'
                , 'location_cities.name as city'
                , DB::raw('CONCAT(IFNULL(location_countries.name, "")
                    , ", "
                    , IFNULL(location_region.name, "")
                    , ", "
                    , IFNULL(location_cities.name, " ")) AS location')
            )
            ->where($where)
            ->orderByDesc('users_info.karma')
            ->simplePaginate(20);
    }



}
