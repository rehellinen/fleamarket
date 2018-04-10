<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/10
 * Time: 12:12
 */

namespace app\common\service;

use think\Exception;
use app\common\exception\WeChatException;

class SellerToken extends Token
{
    protected $code;
    protected $appId;
    protected $appSecret;
    protected $loginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('weixin.bis_app_id');
        $this->appSecret = config('weixin.bis_app_secret');
        $this->loginUrl = sprintf(config('weixin.pay_back_url'), $this->appId, $this->appSecret, $this->code);
    }

    // 主方法
    public function get()
    {
        $result = curl_http($this->loginUrl);
        $jsonResult = json_decode($result, true);
        if(empty($jsonResult)){
            throw new Exception('获取open_id / session_key失败');
        }
        if(array_key_exists('errcode', $jsonResult)){
            // 处理错误的结果
            throw new WeChatException([
                'message' => $jsonResult['errmsg'],
                'status' => $jsonResult['errcode']
            ]);
        }else{
            return $token = $this->grantToken($jsonResult);
        }
    }
}