<?php

namespace App\Http\Controllers\WeChat;

use App\Http\Controllers\Controller;
use App\Models\RaffleDrawList;
use App\Models\RaffleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Overtrue\EasySms\EasySms;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $shopValidator = Validator::make($request->all(), [
            'phone' => 'required|digits:11'

        ]);
        if ($shopValidator->fails()) {
            return response(['message' => $shopValidator->errors()->first()], 500);
        }
        $action = $request->get('action', 'perfectingShopInfo');
        $cache = \Cache::get($action . '_SMS' . $request->getClientIp());
        if ($cache) {
            return response(['message' => '发送次数过多，请稍后再试'], 500);
        }

        $easySms = new EasySms(config('sms'));

        $code = rand(1000, 9999);
        try {
            $result = $easySms->send($request->get('phone'), [
                'content' => function ($gateway) use ($code) {
                    if ($gateway->getName() == 'yunpian') {
                        return "【snow扫码活动】您的验证码为{$code}，5分钟有效。如非本人操作，请忽略此短信。";
                    }
                    return '';
                },
                'template' => function ($gateway) {
                    if ($gateway->getName() == 'yunpian') {
                        return config('sms.yunpian_template.id');
                    }
                    return config('sms.template.shop_perfecting');
                },
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            \Log::error('aliyun send sms failed', $e->getExceptions());
            return $this->sendSmsByYunpian($easySms, $request->get('phone'), $code);
        }
        if ($result['aliyun'] && $result['aliyun']['status'] == 'success') {
            \Cache::put($action . '_SMS' . ($request->server('HTTP_X_FORWARDED_FOR')[0] ?: $request->getClientIp()), $code, 60);
            \Cache::put($action . '_SMS' . $request->get('phone'), $code, 60);

            return response()->noContent();
        } else {
            return $this->sendSmsByYunpian($easySms, $request->get('phone'), $code);
        }
    }

    private function sendSmsByYunpian($easySms, $phone, $code)
    {
        try {
            $easySms->send($phone, [
                'content' => "【snow扫码活动】您的验证码为{$code}，5分钟有效。如非本人操作，请忽略此短信。",
                'template' => config('sms.yunpian_template.id'),
                'data' => [
                    'code' => $code
                ],
            ], ['yunpian']);
            return response()->noContent();
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            \Log::error('yunpian send sms failed', $e->getExceptions());
            return response(['message' => '发送过于频繁，请稍后再试'], 500);
        }
    }

    public function judgeSms(Request $request)
    {
        if (config('sms.jump_sms') == 0 || $request->get('phone_code') != config('sms.jump_sms')) {
            $phoneCode = \Cache::get('perfectingShopInfo_SMS' . $request->get('phone'));

            if (!$phoneCode) {
                return response(['message' => '验证码已失效或未发送验证码'], 500);
            }

            if ($phoneCode != $request->get('phone_code') && $request->get('phone_code') != config('sms.jump_sms')) {
                return response(['message' => '验证码错误'], 500);
            }
        }
        if (!empty($request->get('openId'))) {
            RaffleUser::whereOpenid($request->get('openId'))->update([
                'phone' => $request->get('phone')
            ]);
            RaffleDrawList::whereOpenid($request->get('openId'))
                ->where(function ($query) {
                    $query->where('phone', '=', "")->orWhereNull('phone');
                })->update([
                    'phone' => $request->get('phone')
                ]);
        }
        return response(['message' => '验证码正确！'], 200);
    }

}
