<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/31
 * Time: 14:14
 */

namespace app\api\controller\v1;

use app\common\exception\SuccessMessage;
use app\common\service\WxNotify;
use app\common\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerSellerShopScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder($order_identify = '')
    {
        (new \app\common\validate\Order())->goCheck('pay');
        $pay = new PayService($order_identify);
        $res = $pay->pay();
        throw new SuccessMessage([
            'message' => '获取微信支付预订单参数成功',
            'data' => $res
        ]);
    }

    public function receiveNotify()
    {
        $notify = (new WxNotify());
        $notify->Handle();
    }
}