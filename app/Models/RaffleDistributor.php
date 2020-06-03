<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleDistributor
 *
 * @property int $id
 * @property string|null $name 经销商名称
 * @property string|null $reward 存入奖金
 * @property mixed|null $win_location 中奖范围标点
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereReward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDistributor whereWinLocation($value)
 * @mixin \Eloquent
 */
class RaffleDistributor extends Model
{
    protected $guarded = ['id'];
}
