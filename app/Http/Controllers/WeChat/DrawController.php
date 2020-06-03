<?php

namespace App\Http\Controllers\WeChat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Factory;
use Carbon\Carbon;
use App\Models\RaffleJackpot;
use App\Models\RaffleState;
use App\Models\RaffleDrawList;
use App\Models\RaffleUser;
use App\Services\Expands\WxService;
use App\Services\RaffleStateService;
use App\Services\TxLocationService;
use Illuminate\Http\Request;

class DrawController extends Controller
{

    protected $wxService;
    public function __construct(WxService $wxService)
    {
        $this->wxService = $wxService;
    }

    public function draw(Request $request)
    {
        $id = \Hashids::decode($request->get('id'))['0'];
        $openId = $request->get('openId', '');
        //$openId = \Cache::get($openId);
        //if (!$openId) {
        //    return response(['code' => '406', 'message' =>'抱歉！', 'message2' => '请重新授权']);
        //}
        $loaction = $request->get('lat').','.$request->get('lng');

        $user = RaffleUser::whereOpenid($openId)->first();
        $logId = RaffleDrawList::whereHas(
            'logId', function ($query) use ($id) {
            $query->select('id', 'status');
        })->with(['logId' => function ($query) use ($id) {
            $query->select('id', 'status');
        }])->select('id', 'url_code_log_id', 'openid')->where('id', $id)->first();

        //判断今天抽奖次数
        $date = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
        $drawCount = RaffleDrawList::whereOpenid($openId)
            ->whereBetween('win_time', [
            $date->startOfDay()->toDateTimeString(),
            $date->endOfDay()->toDateTimeString()
        ])->count();

        if ($drawCount > 10) {
            return response(['code' => '406', 'message' =>'抱歉！', 'message2' => '您今天抽奖次数已达上限，请明天再来。']);
        }
        if ($logId->openid) {
            return response(['code' => '406', 'message' =>'抱歉！', 'message2' => '这个二维码已参与过此活动。']);
        }
        if ($logId->logId->status == 0) {
            return response(['code' => '406', 'message' => '抱歉！', 'message2' => '此码还没启用，请稍后再试。']);
        }

        //根据坐标判断经销商
        $res = (new TxLocationService())->location($loaction);
        if ($res->result->count == 0) {
            return response(['code' => '406', 'message' => '抱歉！', 'message2' => '您不在抽奖活动区域内。']);
        }
        $distributorIds = [];
        foreach ($res->result->data as $k=>$v) {
            $distributorIds[] = $v->x->distributor_id;
        }

        if (count($distributorIds) > 1) { //在大奖区域
            $data['distributor_id'] = $distributorIds[0];
            $data['is_grand'] = 1;
        } else {
            $data['distributor_id'] = $distributorIds[0];
            $data['is_grand'] = 0;
        }
        //随机抽奖
        $draw = (new RaffleStateService())->draw($data['is_grand']);

        DB::beginTransaction();
        try {
            //清掉奖池数据
            foreach ($draw as $k=>$v) {
                $drawId = intval($v->id);
                $raffleState = RaffleState::whereId($v->raffle_state_id)->first();
                $isWin = RaffleState::Draw['yes'];
                if ($raffleState->grade == 0) {
                    $isWin = RaffleState::Draw['no'];
                }
                $updateStatus = RaffleDrawList::whereId($id)->where('openid', null)->update([
                    'openid'=> $openId,
                    'nickname'=> $user->nickname,
                    'avatar'=> $user->avatar,
                    'phone'=> '',
                    'amount'=> $raffleState->grade,
                    'win_time'=> Carbon::now(),
                    'raffle_state_id'=> $raffleState->id,
                    'is_grand'=> $raffleState->is_grand,
                    'lat'=> $request->get('lat'),
                    'lng'=> $request->get('lng'),
                    'distributor_id'=> $data['distributor_id'],
                    'is_win'=> $isWin
                ]);
                if ($updateStatus == 0) {
                    return response(['code' => '406', 'message' => '抱歉！', 'message2' => '此奖已被抽取。']);
                }
                $delStatus = RaffleJackpot::whereId($drawId)->delete();
                if ($delStatus == 0) {
                    return response(['code' => '406', 'message' => '抱歉！', 'message2' => '此奖已被抽取。']);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response(null, 403);
        }

        //中小奖发钱，谢谢回顾和大奖排除
        if ($raffleState->grade > 0 && !$raffleState->is_grand == RaffleDrawList::IS_GRAND['yes']) {
            $resultWxPay = $this->wxService->wxPay($openId, $raffleState->grade);
            if ($resultWxPay['return_code'] == 'SUCCESS' && $resultWxPay['result_code'] == 'SUCCESS') {
                $payload = [];
                $payload['is_release'] = RaffleDrawList::IS_RELEASE['yes'];
                $payload['release_time'] = $resultWxPay['payment_time'];
                $payload['partner_trade_no'] = $resultWxPay['partner_trade_no'];
                $payload['payment_no'] = $resultWxPay['payment_no'];
                RaffleDrawList::whereId($id)->update($payload);
            }
        }

        return response(['draw' => $draw, 'isGrand' => $raffleState->is_grand, 'amount' => $raffleState->grade]);
    }



}
