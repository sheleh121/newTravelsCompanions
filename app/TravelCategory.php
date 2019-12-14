<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TravelCategory
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $event
 * @property-read int|null $event_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TravelCategory extends Model
{
    //
    public function event()
    {
        return $this->hasMany('App\Travel');
    } 
}
