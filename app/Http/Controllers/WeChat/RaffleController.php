<?php

namespace App\Http\Controllers\WeChat;

use App\Http\Controllers\Controller;
use App\Models\RaffleActivity;
use App\Models\RaffleDrawList;
use App\Models\RaffleUser;
use App\Services\Expands\WxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaffleController extends Controller
{
    protected $wxService;

    public function __construct(WxService $wxService)
    {
        $this->wxService = $wxService;
    }

    public function winRecord(Request $request)
    {
        $openId = $request->get('openId');
        $winRecordList = [];
        if (!empty($openId)) {
            $winRecordList = RaffleDrawList::select(['id', 'win_time', 'amount', 'is_release'])
                ->where([
                    'openid' => $openId,
                    'is_win' => RaffleDrawList::IS_WIN['yes']])
                ->orderByDesc('win_time')
                ->paginate(20);
        }
        foreach ($winRecordList as $k => $v_draw) {
            $v_draw->code_id = \Hashids::encode($v_draw->id);
            unset($v_draw->id);
        }
        return response($winRecordList);
    }

    public function isHavePhone(Request $request)
    {
        $openId = $request->get('openId');
        if (!empty($openId)) {
            $isHavePhone = RaffleUser::whereOpenid($openId)->where('phone', '!=', '')->first();
            if (!$isHavePhone) {
                return response(['isHavePhone' => 1]);
            } else {
                RaffleDrawList::whereOpenid($request->get('openId'))
                    ->where(function ($query) {
                        $query->where('phone', '=', "")->orWhereNull('phone');
                    })->update([
                        'phone' => $isHavePhone->phone
                    ]);
                return response(['isHavePhone' => 0]);
            }
        } else {
            return response(['message' => '获取不到您的信息！'], 403);
        }

    }

    public function repost(Request $request)
    {
        //$result = $this->wxService->wxPay('oD57YwZlpbQnZxv5XgKXAwJk-EZY', 0.3);
        $id = $request->get('id');//二维码id瓶盖
        $openId = $request->get('openId');
        $id = \Hashids::decode($id)[0];
        $repostResult = 0;//默认转发不合格
        if (!empty($openId) && !empty($id)) {
            $drawListResult = RaffleDrawList::whereId($id)->first();
            if (!empty($drawListResult)) {
                if ($drawListResult->openid == $openId
                    && $drawListResult->is_transmit == RaffleDrawList::IS_TRANSMIT['no']
                    && $drawListResult->is_release == RaffleDrawList::IS_RELEASE['no']
                    && ($drawListResult->release_time == "" || $drawListResult->release_time == null)
                    && ($drawListResult->partner_trade_no == "" || $drawListResult->partner_trade_no == null)
                    && ($drawListResult->payment_no == "" || $drawListResult->payment_no == null)
                    && $drawListResult->is_grand == RaffleDrawList::IS_GRAND['yes']) {
                    $resultWxPay = $this->wxService->wxPay($openId, $drawListResult->amount);
                    if ($resultWxPay['return_code'] == 'SUCCESS' && $resultWxPay['result_code'] == 'SUCCESS') {
                        $payload = [];
                        $payload['is_transmit'] = RaffleDrawList::IS_TRANSMIT['yes'];
                        $payload['is_release'] = RaffleDrawList::IS_RELEASE['yes'];
                        $payload['release_time'] = $resultWxPay['payment_time'];
                        $payload['partner_trade_no'] = $resultWxPay['partner_trade_no'];
                        $payload['payment_no'] = $resultWxPay['payment_no'];
                        $drawListResult->update($payload);
                        $repostResult = 1;
                    }
                }
            }
        }
        if ($repostResult == 1) {
            return response(['message' => '系统将自动发送到您的微信零钱中，请注意查收！'], 200);
        } else {
            return response(['message' => '转发失败！'], 403);
        }
    }

    public function winnerUsers(Request $request)
    {
        $winnerUsersList = RaffleDrawList::select(['nickname', 'amount'])
            ->where([
                'is_win' => RaffleDrawList::IS_WIN['yes']])
            ->where('amount', '>', 1)
            ->where('nickname', '!=', '')
            ->orderByDesc('win_time')
            ->paginate(20);
        return response($winnerUsersList);
    }

    public function raffleActivityDetail(Request $request)
    {
        $raffleActivityDescription = RaffleActivity::first()->description;
        if (!$raffleActivityDescription) {
            return response(['message' => 'not found activity value'], 500);
        }
        return response(['description' => $raffleActivityDescription]);
    }

}
