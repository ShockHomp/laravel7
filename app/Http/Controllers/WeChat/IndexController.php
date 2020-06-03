<?php

namespace App\Http\Controllers\WeChat;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Carbon\Carbon;
use App\Models\RaffleUser;
use App\Models\RaffleJackpot;
use App\Models\RaffleDrawList;
use App\Services\WeChatService;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function __construct()
    {
        //$this->config = [
        //    'app_id' => config('wechat.official_account.default.app_id'),
        //    'secret' => config('wechat.official_account.default.secret'),
        //    'scopes'   => ['snsapi_base'],
        //    'callback' => 'https://dandong-admin.fasthome.maigengduo.com/api/wechat/callback',
        //
        //    // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        //    'response_type' => 'array',
        //];

        $this->appid = config('wechat.official_account.default.app_id');
        $this->secret = config('wechat.official_account.default.secret');
    }

    public function index(Request $request)
    {
        $code = $request->get('code', '');
        $oldState =$request->get('state');
        $state = \Hashids::decode($request->get('state'))['0'];

        $arr = (new WeChatService())->getUserInfo($code);
        \Log::info("Login",['openId'=>$arr['userinfo']['openid'], 'state'=>$state]);

        //$token = $this->createToken($code, $arr['userinfo']['openid']);
        //\Log::info($token);

        $user = RaffleUser::whereOpenid($arr['userinfo']['openid'])->first();
        if (empty($user) && !empty($arr['userinfo']['openid'])) {
            $userId = RaffleUser::create([
                'openid' => $arr['userinfo']['openid'],
                'nickname' => $arr['userinfo']['nickname'],
                'avatar' => $arr['userinfo']['headimgurl']
            ]);
        }

        if (!empty($arr) || !empty($userId)) {
            if ($state) {
                $raffleDrawList = RaffleDrawList::whereId($state)->first();
                if ($raffleDrawList['openid']) {
                    header("Location:https://d.fast-home.cn/s?open_id=".$arr['userinfo']['openid']);
                } else {
                    header("Location:https://d.fast-home.cn/h?open_id=".$arr['userinfo']['openid']."&id=".$oldState);
                }
            } else {
                header("Location:https://d.fast-home.cn/s?open_id=".$arr['userinfo']['openid']."&id=0");
            }
        } else {
            header("Location:https://d.fast-home.cn/h?open_id=1");
        }

        return $arr;
    }

    public function getJssdk(Request $request)
    {
        $url = $request->get('url', '');
        $res = (new WeChatService())->getSignPackage($url);
        return $res;
    }

    protected function createToken($code, $openId)
    {
        $token = bcrypt($code);
        \Cache::put($token, $openId, Carbon::now()->addHours(1));
        return $token;
    }


}
