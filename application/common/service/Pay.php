<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/31
 * Time: 15:03
 */

namespace app\common\service;

use app\common\exception\OrderException;
use app\common\exception\TokenException;
use enum\OrderEnum;
use think\Exception;
use app\common\model\Order as OrderModel;
use app\common\service\Order as OrderService;
use think\Loader;
use think\Log;

Loader::import('pay.WxPay',EXTEND_PATH , '.Api.php');

class Pay
{
    private $orderNO;
    private $orderID;
    private $orderIDs = [];

    public function __construct($orderIdentify)
    {
        if(!$orderIdentify){
            throw new Exception('订单号不能为空');
        }elseif (is_numeric($orderIdentify)){
            $this->orderID = $orderIdentify;
            $order = (new OrderModel())->where('id', '=', $orderIdentify)->find();
            $this->orderNO = $order['order_no'];
        }else{
            $this->orderNO = $orderIdentify;
        }
    }

    public function pay()
    {
        // 检查订单状态是否合法
        $this->checkOrder();
        // 进行库存量检查
        $orderService = new OrderService();
        $orderStatus = $orderService->checkStock($this->orderIDs);

        return $this->makeWxPreOrder($orderStatus['orderPrice']);
    }

    private function checkOrder()
    {
        // 检查是否有该订单
        if($this->orderID){
            $orders = (new OrderModel())->where('id', '=', $this->orderID)->select()->toArray();
        }else{
            $orders = (new OrderModel())->where('order_no', '=', $this->orderNO)->select()->toArray();
        }

        foreach ($orders as $order){
            // 检查订单是否存在
            if(!$order){
                throw new OrderException();
            }

            // 检测订单是否属于当前用户
            Token::isValidOperate($order['buyer_id']);

            // 检查订单是否已被支付
            if($order['status'] != OrderEnum::UNPAID){
                throw new OrderException([
                    'message' => '该订单已支付',
                    'status' => 80003
                ]);
            }
            array_push($this->orderIDs, $order['id']);
        }
        return true;
    }

    private function makeWxPreOrder($totalPrice)
    {
        $openID = Token::getCurrentTokenVar('openid');
        if(!$openID){
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        if($this->orderID){
            $wxOrderData->SetOut_trade_no($this->orderID);
        }else{
            $wxOrderData->SetOut_trade_no($this->orderNO);
        }
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('易乎');
        $wxOrderData->SetOpenid($openID);
        $wxOrderData->SetNotify_url(config('weixin.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder)
    {
        (new OrderModel())->where('id' , 'in',  $this->orderIDs)->update([
            'prepay_id' => $wxOrder['prepay_id']
        ]);
    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('weixin.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }
}