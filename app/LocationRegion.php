<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LocationRegion
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $event
 * @property-read int|null $event_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationRegion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LocationRegion extends Model
{
    //

    protected $table = 'location_region';
    public function event()
    {
        return $this->hasMany('App\Travel');
    }
}
