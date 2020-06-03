<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleUser
 *
 * @property int $id
 * @property int $openid
 * @property int $nickname
 * @property int $avator
 * @property int $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereAvator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RaffleActivity extends Model
{
    protected $guarded = ['id'];
    protected $table = 'raffle_activitys';
}
