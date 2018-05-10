<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:13
 */

namespace app\admin\controller;

use app\common\model\Goods;
use app\common\model\Seller;
use app\common\model\Buyer;
use app\common\model\Order;
use enum\StatusEnum;
use enum\TypeEnum;

class Index extends BaseController
{
    public function index()
    {
        // 自营商品
        $newGoodsCount = (new Goods())->where([
            'status' => StatusEnum::NORMAL,
            'type' => TypeEnum::NewGoods
        ])->count();
        $sellerCount = (new Seller())->getNormalCount();
        $buyerCount = (new Buyer())->getNormalCount();
        $dealCount = (new Order())->getNormalCount();
        return $this->fetch('', [
            'newGoodsCount' => $newGoodsCount,
            'sellerCount' => $sellerCount,
            'buyerCount' => $buyerCount,
            'dealCount' => $dealCount
        ]);
    }
}