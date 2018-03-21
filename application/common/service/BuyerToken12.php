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
use enum\ScopeEnum;

class BuyerToken12 extends Token
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

    public function get()
    {
        $result = curl_get($this->loginUrl);
        $wxResult = json_decode($result, true);

        if(empty($wxResult)) {
            throw new WeChatException([
                'message' => '爬取数据错误或者微信服务器错误',
                'status' => '10006'
            ]);
        }else{
            $loginFail = array_key_exists('errcode', $wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                $token = $this->grantToken($wxResult);
            }
        }

        return $token;
    }

    private function grantToken($wxResult)
    {
        $openID = $wxResult['openid'];
        $buyer = model('buyer')->getByOpenID($openID);

        if($buyer){
            $buyerID = $buyer->id;
        }else{
            $buyerID = $this->newBuyer($openID);
        }

        $cachedValue = $this->prepareCachedValue($wxResult, $buyerID);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expireIn = config('admin.token_expire_in');

        $request = cache($key, $value, $expireIn);

        if(!$request){
            throw new TokenException([
                'status' => 10005,
                'message' => '服务器缓存异常'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult, $buyerID)
    {
        $cachedValue = $wxResult;
        $cachedValue['buyerID'] = $buyerID;
        $cachedValue['scope'] = ScopeEnum::Buyer;

        return $cachedValue;
    }

    private function newBuyer($openID)
    {
        $buyer = model('buyer')->create([
            'openid' => $openID
        ]);

        return $buyer->id;
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'message' => $wxResult['errmsg'],
            'status' => $wxResult['errcode']
        ]);
    }
}