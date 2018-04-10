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
use enum\StatusEnum;
use enum\ScopeEnum;

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
        $sellerID = $this->getIDByOpenID($wxResult['openid'], 'Buyer', StatusEnum::Normal);
        // 生成缓存的键与值
        $cachedKey = self::generateToken();
        $cachedValue = $this->prepareCachedValue($wxResult, $sellerID);
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
    private function prepareCachedValue($wxResult, $sellerID)
    {
        $cachedValue = $wxResult;
        $cachedValue['sellerID'] = $sellerID;
        $cachedValue['scope'] = ScopeEnum::Seller;

        return $cachedValue;
    }
}