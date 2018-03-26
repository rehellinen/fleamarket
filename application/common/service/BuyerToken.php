<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:07
 */

namespace app\common\service;


use app\common\exception\TokenException;
use app\common\exception\WeChatException;
use app\common\model\Buyer;
use enum\ScopeEnum;
use think\Exception;

class BuyerToken extends Token
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
            throw new WeChatException([
                'message' => $jsonResult['errmsg'],
                'status' => $jsonResult['errcode']
            ]);
        }else{
            return $token = $this->grantToken($jsonResult);
        }
    }

    // 获取令牌的方法
    private function grantToken($jsonResult)
    {
        $openID = $jsonResult['openid'];
        $buyerID = $this->getIDByOpenID($openID);
        $cachedValue = $this->prepareCachedValue($jsonResult, $buyerID);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    // 存入缓存并返回Token
    private function saveToCache($cachedValue)
    {
        $cachedKey = self::generateToken();
        $cachedValue = json_encode($cachedValue);
        $expire_in = config('admin.token_expire_in');

        $cache = cache($cachedKey, $cachedValue, $expire_in);
        if(!$cache){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'status' => 10001
            ]);
        }
        return $cachedKey;
    }

    // 生成缓存数据
    private function prepareCachedValue($jsonResult, $buyerID)
    {
        $cachedValue = $jsonResult;
        $cachedValue['buyerID'] = $buyerID;
        $cachedValue['scope'] = ScopeEnum::Buyer;

        return $cachedValue;
    }

    // 获取用户ID的方法
    private function getIDByOpenID($openID)
    {
        $buyer = (new Buyer())->getByOpenID($openID);
        if(!$buyer){
            $buyerID = (new Buyer())->insertGetId([
                'open_id' => $openID
            ]);
        }else{
            $buyerID = $buyer->id;
        }
        return $buyerID;
    }
}


