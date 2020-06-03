<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleState
 *
 * @property int $id
 * @property string|null $grade 奖金档次
 * @property string|null $win_chance 中奖几率
 * @property int $prize_number 分配个数
 * @property int $is_grand 0、不是大奖 1、大奖
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereIsGrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState wherePrizeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereWinChance($value)
 * @mixin \Eloquent
 * @property int $is_not_win 0、奖金 1、谢谢惠顾
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleState whereIsNotWin($value)
 */
class RaffleState extends BaseModel
{
    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    const Draw = [
        'yes' => 1,
        'no' => 0
    ];

}
