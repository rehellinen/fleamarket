<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/10
 * Time: 12:12
 */

namespace app\common\service;

use app\common\exception\TokenException;
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
        $userArr = $this->isRegister($wxResult['openid']);
        // 生成缓存的键与值
        $cachedKey = self::generateToken();
        $cachedValue = $this->prepareCachedValue($wxResult, $userArr);
        // 进行缓存
        $token = $this->saveToCache($cachedKey, $cachedValue);

        $userArr['token'] = $token;
        return $userArr;
    }

    /**
     * 根据openID查询是否该用户已经注册为商家 / 二手卖家
     * @return array
     * @throws TokenException
     */
    public function isRegister($openID)
    {
        $seller = model('Seller')->where([
            'open_id' => $openID,
            'status' => ['neq', StatusEnum::DELETED]
        ])->find();

        $shop = model('Shop')->where([
            'open_id' => $openID,
            'status' => ['neq', StatusEnum::DELETED]
        ])->find();

        if($seller){
            return [
                'type' => 'seller',
                'id' => $seller['id'],
                'status' => $seller['status']
            ];
        }elseif($shop){
            return [
                'type' => 'shop',
                'id' => $shop['id'],
                'status' => $shop['status']
            ];
        }else{
            throw new TokenException([
                'httpCode' => 404,
                'status' => 50001,
                'message' => '用户未注册为商家 / 二手卖家'
            ]);
        }
    }

    /**
     * 准备缓存的数据结构
     * @param array $wxResult 微信返回的结果
     * @param array $userArr
     * @return array 要储存的信息
     */
    private function prepareCachedValue($wxResult, $userArr)
    {
        $cachedValue = $wxResult;
        if($userArr['type'] === 'seller'){
            $cachedValue['scope'] = ScopeEnum::SELLER;
            $cachedValue['sellerID'] = $userArr['id'];
        }else{
            $cachedValue['scope'] = ScopeEnum::SHOP;
            $cachedValue['shopID'] = $userArr['id'];
        }

        return $cachedValue;
    }
}