<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TravelType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TravelCategory[] $category
 * @property-read int|null $category_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Travel[] $event
 * @property-read int|null $event_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TravelType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TravelType extends Model
{
	public function event()
    {
        return $this->hasMany('App\Travel');
    }
  	public function category()
  	{
    	return $this->belongsToMany('App\TravelCategory');
  	}    
}
