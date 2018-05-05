<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:07
 */

namespace app\common\service;

use app\common\exception\WeChatException;
use enum\ScopeEnum;
use enum\StatusEnum;
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
        // 根据openID获取用户ID
        $buyerID = $this->getIDByOpenID($wxResult['openid'], 'Buyer', StatusEnum::NORMAL);
        // 生成缓存的键与值
        $cachedKey = self::generateToken();
        $cachedValue = $this->prepareCachedValue($wxResult, $buyerID);
        // 进行缓存
        $token = $this->saveToCache($cachedKey, $cachedValue);
        return $token;
    }

    /**
     * 准备缓存的数据结构
     * @param array $wxResult 微信返回的结果
     * @param $buyerID
     * @return array 要储存的信息
     */
    private function prepareCachedValue($wxResult, $buyerID)
    {
        $cachedValue = $wxResult;
        $cachedValue['buyerID'] = $buyerID;
        $cachedValue['scope'] = ScopeEnum::BUYER;

        return $cachedValue;
    }
}


