<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LocationCountry
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $event
 * @property-read int|null $event_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LocationCountry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LocationCountry extends Model
{
    //

    protected $table = 'location_countries';
    public function event()
    {
        return $this->hasMany('App\Travel');
    }
}
