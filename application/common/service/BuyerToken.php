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
use think\Exception;

class BuyerToken extends Token
{
    protected $code;
    protected $appId;
    protected $appSecret;
    protected $loginUrl;

    /*
     * 初始化成员变量
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('weixin.app_id');
        $this->appSecret = config('weixin.app_secret');
        $this->loginUrl = sprintf(config('weixin.url'), $this->appId, $this->appSecret, $this->code);
    }

    /**
     * 主方法
     * @return string Token令牌
     * @throws Exception 微信服务器没有响应
     * @throws WeChatException
     */
    public function get()
    {
        // 爬取微信服务器返回的结果
        $wxResult = $this->getResultFromWx();
        //
        $openID = $wxResult['openid'];
        $buyerID = $this->getIDByOpenID($openID, 'Buyer');
        $cachedValue = $this->prepareCachedValue($wxResult, $buyerID);
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
}


