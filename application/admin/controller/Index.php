<?php
namespace app\admin\controller;
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:13
 */
class Index extends Base
{
    public function index()
    {
        $goodsCount = model('Goods')->getGoodsCount();
        $sellerCount = model('Seller')->getSellerCount();
        $buyerCount = model('Buyer')->getBuyerCount();
        $dealCount = model('Deal')->getDealCount();
        return $this->fetch('', [
            'goodsCount' => $goodsCount,
            'sellerCount' => $sellerCount,
            'buyerCount' => $buyerCount,
            'dealCount' => $dealCount
        ]);
    }
}