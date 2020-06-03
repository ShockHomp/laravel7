<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleUrlCodeLog
 *
 * @property int $id
 * @property int $count 生成二维码数量
 * @property string|null $start_time 启用时间
 * @property string|null $end_time 终止时间
 * @property int $user_id 生成账号
 * @property int $status 0、未启用 1、启用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleUrlCodeLog whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 */
class RaffleUrlCodeLog extends BaseModel
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
