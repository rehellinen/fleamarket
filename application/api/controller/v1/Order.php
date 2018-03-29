<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 19:49
 */

namespace app\api\controller\v1;

use app\common\exception\SuccessMessage;
use app\common\validate\Order as OrderValidate;
use app\common\service\Token as TokenService;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerScope' => ['only', 'placeOrder']
    ];

    public function placeOrder()
    {
        (new OrderValidate())->goCheck('order');
        $post = $this->request->post();
        $goods = $post['goods'];
        $buyerID = TokenService::getBuyerID();

        $order = new \app\common\service\Order();
        $status = $order->place($buyerID, $goods);
        throw new SuccessMessage([
            'message' => '创建订单成功',
            'data' => $status
        ]);
    }
}