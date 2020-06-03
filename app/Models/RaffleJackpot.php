<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleJackpot
 *
 * @property int $id
 * @property int $raffle_state_id 奖金档次id
 * @property int $use_status 0、未使用 1、已使用
 * @property int $raffle_draw_list_id 二维码url id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereRaffleDrawListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereRaffleStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleJackpot whereUseStatus($value)
 * @mixin \Eloquent
 */
class RaffleJackpot extends Model
{
    protected $guarded = ['id'];

    const USE_STATUS = [
        'yes' => 1, //已使用
        'no' => 0 // 未使用
    ];

    public function statusId()
    {
        return $this->hasOne(RaffleState::class, 'id', 'raffle_state_id');
    }

}
