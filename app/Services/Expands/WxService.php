<?php


namespace App\Services\Expands;


class WxService
{
    public function wxPay($openId, $amount)
    {
        $payment = \EasyWeChat::payment();
        //统一下单接口
        $result = $payment->transfer->toBalance([
            'partner_trade_no' => uniqid(), // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => $openId,
            'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => 'name', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => $amount * 100, // 企业付款金额，单位为分
            'desc' => '您的中奖金额已到账', // 企业付款操作说明信息。必填
        ]);
        return $result;
    }
}