<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 19:49
 */

namespace app\api\controller\v2;

use app\common\exception\OrderException;
use app\common\exception\SuccessMessage;
use app\common\validate\Common;
use app\common\validate\Order as OrderValidate;
use app\common\service\Token as TokenService;
use app\common\model\Order as OrderModel;
use enum\OrderEnum;
use enum\StatusEnum;
use enum\TypeEnum;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerScope' => ['only' => 'placeOrder,deleteOrder,confirm'],
        'checkSellerShopScope' => ['only' => 'withdraw,getTotalPrice,deliver'],
        'checkBuyerSellerShopScope' => ['only' => 'getDetail, getOrder']
    ];

    /**
     * 买家下单API
     * @throws SuccessMessage
     */
    public function placeOrder()
    {
        (new OrderValidate())->goCheck('order');
        $post = $this->request->post();
        $goods = $post['goods'];

        $order = new \app\common\service\Order();
        $status = $order->place($goods, TokenService::getBuyerID());
        throw new SuccessMessage([
            'message' => '创建订单成功',
            'data' => $status
        ]);
    }

    /**
     * 买家 / 二手商家 / 自营商家获取订单
     * @param int $status 0表示全部订单
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws OrderException
     * @throws SuccessMessage
     */
    public function getOrder($status, $page = 1, $size = 10)
    {
        (new Common())->goCheck('page');
        $buyerID = TokenService::getBuyerID();
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');
        // status为0表示获取所有订单
        if($status == 0){
            $status = [OrderEnum::UNPAID, OrderEnum::PAID, OrderEnum::COMPLETED, OrderEnum::DELIVERED];
        }
        // status为-2表示获取收支明细页面的订单
        if($status == -2){
            $status = [OrderEnum::COMPLETED, OrderEnum::WITHDRAWING, OrderEnum::WITHDRAWN];
        }
        if($buyerID){
            $res = (new OrderModel())->getOrderByUser($buyerID, $status, $page, $size);
        }elseif($sellerID){
            $type = 2;
            $id = $sellerID;
            $res = (new OrderModel())->getOrderBySellerOrShop($type, $status, $id, $page, $size);
        }elseif($shopID){
            $type = 1;
            $id = $shopID;
            $res = (new OrderModel())->getOrderBySellerOrShop($type, $status, $id, $page, $size);
        }

        if($res->isEmpty()){
            throw new OrderException([
                'data' => [
                    'data' => []
                ]
            ]);
        }
        throw new SuccessMessage([
           '获取订单成功',
            'data' => $res->toArray()
        ]);
    }

    /**
     * 买家 / 二手商家 / 自营商家获取订单详情
     * @param $id
     * @param $type
     * @throws OrderException
     * @throws SuccessMessage
     */
    public function getDetail($id, $type)
    {
        (new Common())->goCheck('id');
        $buyerID = TokenService::getBuyerID();
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');

        if($buyerID){
            $order = (new OrderModel)->getBuyerOrderByID($id, $buyerID, $type);
        }
        if($sellerID){
            $order = (new OrderModel)->getSellerOrShopDetailByID($id, $sellerID, 2);
        }
        if($shopID){
            $order = (new OrderModel)->getSellerOrShopDetailByID($id, $shopID, 1);
        }

        if(!$order){
            throw new OrderException();
        }
        $order = $order->hidden(['prepay_id']);
        throw new SuccessMessage([
            'message' => '获取订单详情成功',
            'data' => $order
        ]);
    }

    /**
     * 卖家进行订单发货
     * @param int $id 订单ID
     * @throws SuccessMessage
     */
    public function deliver($id)
    {
        (new Common())->goCheck('id');
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');

        if($sellerID){
            $res = (new OrderModel)->updateOrderStatus($id, $sellerID, TypeEnum::OldGoods, OrderEnum::DELIVERED);
        }

        if($shopID){
            $res = (new OrderModel)->updateOrderStatus($id, $shopID, TypeEnum::NewGoods, OrderEnum::DELIVERED);
        }

        if($res){
            throw new SuccessMessage([
                'message' => '发货成功'
            ]);
        }
    }

    /**
     * 卖家确认收货
     * @param int $id 订单ID
     * @throws SuccessMessage
     */
    public function confirm($id)
    {
        (new Common())->goCheck('id');
        $buyerID = TokenService::getBuyerID();

        $res = (new OrderModel())->where([
            'id' => $id,
            'buyer_id' => $buyerID,
        ])->update(['status' => OrderEnum::COMPLETED]);

        if($res){
            throw new SuccessMessage([
                'message' => '确认收货成功'
            ]);
        }
    }

    /**
     * 用户删除订单
     * @param int $id 订单ID
     * @throws OrderException 订单不存在
     * @throws SuccessMessage
     */
    public function deleteOrder($id)
    {
        $buyerID = TokenService::getBuyerID();
        $order = (new OrderModel())->where([
            'status' => ['=', OrderEnum::UNPAID],
            'id' => $id
        ])->find()->toArray();

        if(!$order){
            throw new OrderException();
        }

        TokenService::isValidOperate($order['buyer_id']);

        $order['status'] = OrderEnum::DELETE;
        (new OrderModel())->where([
            'status' => ['=', OrderEnum::UNPAID],
            'id' => $id,
            'buyer_id' => $buyerID
        ])->update($order);

        throw new SuccessMessage([
            'message' => '删除订单成功'
        ]);
    }

    /**
     * 商家发起提现
     * @param int $id 订单ID
     * @throws OrderException 订单不存在
     * @throws SuccessMessage
     */
    public function withdraw($id)
    {
        (new Common())->goCheck('id');
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');
        if($sellerID){
            $type = TypeEnum::OldGoods;
            $foreignID = $sellerID;
        }else{
            $type = TypeEnum::NewGoods;
            $foreignID = $shopID;
        }
        $order = (new OrderModel())->where([
            'status' => OrderEnum::COMPLETED,
            'id' => $id,
            'type' => $type,
            'foreign_id' => $foreignID
        ])->find();
        if(!$order){
            throw new OrderException();
        }
        $orderArr = $order->toArray();
        $type = $orderArr['type'];
        TokenService::isValidSellerShop($order->foreign_id, $type);
        $order->status = OrderEnum::WITHDRAWING;
        $res = $order->save();
        if($res){
            throw new SuccessMessage([
               'message' => '发起提现成功'
            ]);
        }
    }

    /**
     * 获取我的收入页面的价格
     */
    public function getTotalPrice()
    {
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');
        if($sellerID){
            $type = TypeEnum::OldGoods;
            $foreignID = $sellerID;
        }else{
            $type = TypeEnum::NewGoods;
            $foreignID = $shopID;
        }
        $orders = (new OrderModel())->where([
            'status' => ['in', [OrderEnum::PAID, OrderEnum::DELIVERED, OrderEnum::COMPLETED, OrderEnum::WITHDRAWING, OrderEnum::WITHDRAWN]],
            'type' => $type,
            'foreign_id' => $foreignID
        ])->select()->toArray();
        $trading = 0;
        $completed = 0;
        $withdrawing = 0;
        $withdrawn = 0;
        foreach ($orders as $order){
            if($order['status'] == OrderEnum::PAID || $order['status'] == OrderEnum::DELIVERED){
                $trading += $order['total_price'];
            }
            if($order['status'] == OrderEnum::COMPLETED){
                $completed += $order['total_price'];
            }
            if($order['status'] == OrderEnum::WITHDRAWING){
                $withdrawing += $order['total_price'];
            }
            if($order['status'] == OrderEnum::WITHDRAWN){
                $withdrawn += $order['total_price'];
            }
        }
        throw new SuccessMessage([
            'data' => [
                'trading' => $trading,
                'completed' => $completed,
                'withdrawing' => $withdrawing,
                'withdrawn' => $withdrawn
            ]
        ]);
    }
}