<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Claim
 *
 * @property int $id
 * @property string $status_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Claim whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Claim extends Model
{
    protected $table = 'status';
}
