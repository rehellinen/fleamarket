<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/9/20
 * Time: 20:16
 */

namespace app\common\service;


use app\common\model\Buyer;

class Template
{
    public function __construct($order)
    {
        $this->prepayId = $order['prepay_id'];
        $this->buyerId = $order['buyer_id'];
        $this->orderId = $order['id'];
        $this->orderNO = $order['order_no'];
        $this->totalPrice = $order['total_price'];
        $this->appId = config('weixin.app_id');
        $this->appSecret = config('weixin.app_secret');
        $this->tokenUrl = sprintf(config('weixin.access_token_url'), $this->appId, $this->appSecret);
        $this->sendUrl = sprintf(config('weixin.send_tpl_url'), $this->getAccessToken());
    }

    public function send()
    {
        $postData = $this->preparePostData();
        $jsonData = curl_http($this->sendUrl, 1, $postData);
        $data = json_decode($jsonData);
        if ($data['errcode'] === 40001) {
            $this->sendUrl = sprintf(config('weixin.send_tpl_url'), $this->getAccessToken(true));
            curl_http($this->sendUrl, 1, $postData);
        }
    }

    /**
     * 获取access_token
     * @param $flag boolean 为true时重新发送请求获取access_token
     * @return mixed
     */
    public function getAccessToken($flag = false)
    {
        $cacheToken = cache('access_token');
        if ($flag || !$cacheToken) {
            $jsonResult = curl_http($this->tokenUrl);
            $res = json_decode($jsonResult, true);
            $accessToken = $res['access_token'];
            cache('access_token', $accessToken, 6000);
            return $accessToken;
        } else {
            return $cacheToken;
        }
    }

    private function preparePostData()
    {
        $buyer = (new Buyer())->getNormalById($this->buyerId);

        $data = [
            // open_id
            'touser' => $buyer['open_id'],
            'template_id' => config('weixin.payTemplate'),
            'page' => 'pages/order-detail/main?id=' . $this->orderId,
            // prepay_id
            'form_id' => $this->prepayId,
            'data' => [
                // 单号
                'keyword1' => ['value' => $this->orderNO],
                // 订单内容
                'keyword2' => ['value' => ''],
                // 下单门店
                'keyword3' => ['value' => ''],
                // 金额
                'keyword4' => ['value' => $this->totalPrice]
            ]
        ];
        return $data;
    }
}