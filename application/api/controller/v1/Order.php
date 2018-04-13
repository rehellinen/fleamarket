<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 19:49
 */

namespace app\api\controller\v1;

use app\common\exception\OrderException;
use app\common\exception\SuccessMessage;
use app\common\validate\Common;
use app\common\validate\Order as OrderValidate;
use app\common\service\Token as TokenService;
use app\common\model\Order as OrderModel;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerScope' => ['only' => 'placeOrder, getBuyerOrder, getDetail'],
        'checkSellerShopScope' => ['only' => 'getSellerOrder']
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
     * 买家获取所有订单
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws OrderException
     * @throws SuccessMessage
     */
    public function getBuyerOrder($page = 1, $size = 14)
    {
        (new Common())->goCheck('page');
        $buyerID = TokenService::getBuyerID();
        $res = (new OrderModel())->getOrderByUser($buyerID, $page, $size);
        $res = $res->hidden(['snap_items', 'prepay_id']);
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
     * 买家获取订单详情
     * @param $id
     * @param $type
     * @throws OrderException
     * @throws SuccessMessage
     */
    public function getDetailBuyer($id, $type)
    {
        (new Common())->goCheck('id');
        $buyerID = TokenService::getBuyerID();
        $order = (new OrderModel)->getOrderByBuyerID($id, $buyerID, $type);
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
     * 二手 / 自营获取所有订单
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws OrderException
     * @throws SuccessMessage
     */
    public function getSellerOrder($page = 1, $size = 14)
    {
        (new Common())->goCheck('page');
        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $res = (new OrderModel())->getOrderBySeller($sellerID, $page, $size);
        $res = $res->hidden(['snap_items', 'prepay_id']);
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
}