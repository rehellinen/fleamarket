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
use enum\OrderEnum;
use enum\StatusEnum;
use enum\TypeEnum;
use \app\common\model\Shop;

class Index extends BaseController
{
    public function index()
    {
        // 自营商品
        $newGoodsCount = (new Goods())->where([
            'status' => StatusEnum::NORMAL,
            'type' => TypeEnum::NewGoods
        ])->count();

        // 二手商品
        $oldGoodsCount = (new Goods())->where([
            'status' => StatusEnum::NORMAL,
            'type' => TypeEnum::OldGoods
        ])->count();

        // 订单数量
        $orderCount = (new Order())->where([
            'status' => ['neq', OrderEnum::DELETE]
        ])->count();

        // 自营商家数量
        $shopCount = (new Shop())->getNormalCount();

        // 二手卖家数量
        $sellerCount = (new Seller())->getNormalCount();

        // 买家数量
        $buyerCount = (new Buyer())->getNormalCount();

        return $this->fetch('', [
            'newGoodsCount' => $newGoodsCount,
            'oldGoodsCount' => $oldGoodsCount,
            'orderCount' => $orderCount,
            'shopCount' => $shopCount,
            'sellerCount' => $sellerCount,
            'buyerCount' => $buyerCount
        ]);
    }
}