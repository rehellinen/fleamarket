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
use app\common\model\Deal;

class Index extends BaseController
{
    public function index()
    {
        $goodsCount = (new Goods())->getNormalCount();
        $sellerCount = (new Seller())->getNormalCount();
        $buyerCount = (new Buyer())->getNormalCount();
        $dealCount = (new Deal())->getNormalCount();
        return $this->fetch('', [
            'goodsCount' => $goodsCount,
            'sellerCount' => $sellerCount,
            'buyerCount' => $buyerCount,
            'dealCount' => $dealCount
        ]);
    }
}