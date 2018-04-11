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
        'checkBuyerScope' => ['only' => 'placeOrder, getBuyerOrder, getDetail']
    ];

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

    public function getDetail($id)
    {
        (new Common())->goCheck('id');
        $buyerID = TokenService::getBuyerID();
        $order = (new OrderModel)->where([
            'buyer_id' => $buyerID,
            'id' => $id
        ])->with(['snapItems' => function($query){
            $query->with('imageId');
        }])->find();
        if(!$order){
            throw new OrderException();
        }
        $order = $order->hidden(['prepay_id']);
        throw new SuccessMessage([
            'message' => '获取订单详情成功',
            'data' => $order
        ]);
    }
}