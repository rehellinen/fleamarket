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

Loader::import('pay.WxPay',EXTEND_PATH , '.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        $orderService = new OrderService();
        // 检查订单状态是否合法
        $this->checkOrder();
        // 进行库存量检查
        $status = $orderService->checkStock($this->orderID);
        // 没有通过库存量检测时的操作
        if(!$status['pass']){
            return $status;
        }
    }

    private function makeWxPreOrder()
    {
        $openID = Token::getCurrentTokenVar('openid');
        if(!$openID){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
    }

    private function checkOrder()
    {
        // 检查是否有该订单
        $order = (new OrderModel())->where('id=' . $this->orderID)->find();
        if(!$order){
            throw new OrderException();
        }
        // 检测订单是否属于当前用户
        Token::isValidOperate($order->buyer_id);

        // 检查订单是否已被支付
        if($order->status != OrderEnum::UNPAID){
            throw new OrderException([
                'message' => '该订单已支付',
                'status' => 80003
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}