<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/31
 * Time: 14:14
 */

namespace app\api\controller\v1;

use app\common\validate\Common;
use app\common\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerSellerShopScope' => ['only', 'getPreOrder']
    ];

    public function getPreOrder($orderID = '')
    {
        (new Common())->goCheck('id');
        $pay = new PayService($orderID);
        $pay->pay();
    }
}