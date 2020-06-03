<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RaffleDrawList
 *
 * @property int $id
 * @property int $url_code_log_id url二维码批量添加的log_id
 * @property string|null $openid 中奖人微信openid
 * @property string|null $nickname 中奖人微信名
 * @property string|null $avatar 中奖人头像
 * @property string|null $phone 中奖人手机号
 * @property string|null $amount 中奖金额
 * @property string|null $win_time 中奖时间
 * @property int|null $raffle_state_id 奖金档次id
 * @property int|null $is_grand 0、不是大奖 1、大奖
 * @property string|null $grand_prize_picture 大奖上传图片
 * @property float|null $lat 中奖人GPS 精度
 * @property float|null $lng 中奖人GPS 纬度
 * @property int|null $distributor_id 奖金来源经销商id
 * @property int|null $id_win 0、未中奖 1、中奖
 * @property int|null $is_release 0、未发放 1、已发放
 * @property string|null $release_time 发放时间
 * @property string|null $partner_trade_no 商户订单号
 * @property string|null $payment_no 微信付款单号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereDistributorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereGrandPrizePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereIdWin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereIsGrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereIsRelease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList wherePartnerTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList wherePaymentNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereRaffleStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereReleaseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereUrlCodeLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RaffleDrawList whereWinTime($value)
 * @mixin \Eloquent
 */
class RaffleDrawList extends Model
{
    protected $guarded = ['id'];

    const IS_WIN = [//是否中奖
        'yes' => 1,
        'no' => 0
    ];

    const IS_TRANSMIT = [
        'yes' => 1,
        'no' => 0
    ];

    const IS_RELEASE = [
        'yes' => 1,
        'no' => 0
    ];

    const IS_GRAND = [
        'yes' => 1,
        'no' => 0
    ];

    public function logId()
    {
        return $this->hasOne(RaffleUrlCodeLog::class, 'id', 'url_code_log_id');
    }

    public function distributor()
    {
        return $this->hasOne(RaffleDistributor::class, 'id', 'distributor_id');
    }

}
