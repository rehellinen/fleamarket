<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:07
 */

namespace app\common\service;


use app\common\exception\WeChatException;
use think\Exception;

class UserToken extends Token
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
            throw new Exception('获取open_id失败，微信服务器错误');
        }else{
            $loginFail = array_key_exists('errcode', $wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult)
    {
        $openID = $wxResult['openid'];
        $buyer = model('user')->getByOpenID($openID);

        if($buyer){
            $buyerID = $buyer->id;
        }else{
            $buyerID = $this->newUser($openID);
        }

        $cachedValue = $this->prepareCachedValue($wxResult, $buyerID);
    }

    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
    }

    private function prepareCachedValue($wxResult, $buyerID)
    {
        $cachedValue = $wxResult;
        $cachedValue['buyerID'] = $buyerID;
        $cachedValue['scope'] = 16;

        return $cachedValue;
    }

    private function newUser($openID)
    {
        $buyer = model('user')::create([
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