<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notice
 *
 * @property int $id
 * @property int $user_id
 * @property int $travel_id
 * @property string $description
 * @property int $seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Travel $travel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notice whereUserId($value)
 * @mixin \Eloquent
 */
class Notice extends Model
{
    protected $table = 'notices';

    protected $fillable = [
        'description'
        ,'travel_id'
        ,'user_id'
    ];

    public function travel()
    {
        return $this->belongsTo('App\Travel');
    }
}
