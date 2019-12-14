<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Image
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $travel_id
 * @property int $decision
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed|null $image
 * @property mixed|null $image_small
 * @property-read \App\Travel $Event
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereDecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereImageSmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereUserId($value)
 * @mixin \Eloquent
 */
class Image extends Model
{

	protected $table = 'images';
	protected $fillable = [
        'travel_id'
        ,'path'
        ,'decision'
        ,'image'
        ,'image_small'
        ,'user_id'

    ];
    public function Event()
    {
        return $this->belongsTo('App\Travel');
    }

}
