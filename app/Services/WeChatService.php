<?php


namespace App\Services;
use Carbon\Carbon;

class WeChatService
{
    public function __construct()
    {
        $this->appid = config('wechat.official_account.default.app_id');
        $this->secret = config('wechat.official_account.default.secret');
    }

    /**
     * 获取个人信息
     */
    public function getUserInfo($code)
    {
        $appid = $this->appid;
        $appsecret =  $this->secret;

        // $code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect";
        //第一步:根据code取得access_token
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        //$oauth2Url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $oauth2 = $this->getJson($oauth2Url);

        //第二步：刷新access_token
        $refresh_token = $oauth2["refresh_token"];
        $get_access_token_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
        $refresh_token = $this->getJson($get_access_token_url);

        //第三步:根据access_token和openid查询用户信息
        $access_token = $refresh_token["access_token"];
        $openid = $refresh_token['openid'];
        $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo = $this->getJson($get_user_info_url);

        return array("openId" => $openid, "userinfo" => $userinfo);

    }

    public function getJson($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }




    /**
     * 获取jssdk
     */
    public function getSignPackage($url)
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        //$url = "https://d.fast-home.cn/h";
        //dd($url);

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "jsapiTicket" => $jsapiTicket,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        //$data = json_decode($this->get_php_file("jsapi_ticket.php"));
        //if ($data->expire_time < time()) {
        //    $accessToken = $this->getAccessToken();
        //    // 如果是企业号用以下 URL 获取 ticket
        //    // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        //    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        //    $res = json_decode($this->httpGet($url));
        //    $ticket = $res->ticket;
        //    if ($ticket) {
        //        $data->expire_time = time() + 7000;
        //        $data->jsapi_ticket = $ticket;
        //        $this->set_php_file("jsapi_ticket.php", json_encode($data));
        //    }
        //} else {
        //    $ticket = $data->jsapi_ticket;
        //}

        $ticket = \Cache::get('jsapi_ticket');
        if (!$ticket) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                \Cache::put('jsapi_ticket', $ticket, Carbon::now()->addHours(2));
            }
        }

        return $ticket;
    }

    private function getAccessToken() {
        //// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        //$data = json_decode($this->get_php_file("access_token.php"));
        //if ($data->expire_time < time()) {
        //    // 如果是企业号用以下URL获取access_token
        //    // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
        //    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->secret";
        //    $res = json_decode($this->httpGet($url));
        //    $access_token = $res->access_token;
        //    if ($access_token) {
        //        $data->expire_time = time() + 7000;
        //        $data->access_token = $access_token;
        //        $this->set_php_file("access_token.php", json_encode($data));
        //    }
        //} else {
        //    $access_token = $data->access_token;
        //}

        $access_token = \Cache::get('access_token');
        if (!$access_token) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->secret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                \Cache::put('access_token', $access_token, Carbon::now()->addHours(2));
            }
        }

        return $access_token;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function get_php_file($filename) {
        return trim(substr(file_get_contents($filename), 15));
    }

    private function set_php_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }


}