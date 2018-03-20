<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:07
 */

namespace app\common\service;


use app\common\exception\WeChatException;
use app\common\model\Buyer;
use think\Exception;

class UserToken
{
    protected $code;
    protected $appId;
    protected $appSecret;
    protected $loginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('weixin.app_id');
        $this->appSecret = config('weixin.app_secret');
        $this->loginUrl = sprintf(config('weixin.url'), $this->appId, $this->appSecret, $this->code);
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
            $this->processLoginError($jsonResult);
        }else{
            $token = $this->grantToken($jsonResult);
        }
    }

    // 获取令牌的方法
    private function grantToken($jsonResult)
    {
        $openid = $jsonResult['openid'];
        $buyer = (new Buyer())->getByOpenID($openid);

        if(!$buyer){
            $buyerID = $this->insertUser($openid);
        }else{
            $buyerID = $buyer->id;
        }
    }

    // 新增用户的方法
    private function insertUser($openID)
    {
        $buyerID = (new Buyer())->insertGetId([
            'data' => $openID
        ]);
        return $buyerID;
    }

    // 处理调取微信服务器接口发生错误的方法
    private function processLoginError($jsonResult)
    {
        throw new WeChatException([
            'message' => $jsonResult['errmsg'],
            'status' => $jsonResult['errcode']
        ]);
    }
}


