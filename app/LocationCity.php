<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LocationCity
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $event
 * @property-read int|null $event_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LocationCity extends Model
{
    //

    protected $table = 'location_cities';
    public function event()
    {
        return $this->hasMany('App\Travel');
    }
}
