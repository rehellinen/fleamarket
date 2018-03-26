<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 21:11
 */

namespace app\common\service;


use app\common\model\Goods;

class Order
{
    // 客户端提交的订单中商品信息
    protected $clientGoods;
    // 真实的商品信息
    protected $serverGoods;
    // 买家ID
    protected $buyerID;

    public function place($buyerID, $clientGoods)
    {
        $this->clientGoods = $clientGoods;
        $this->serverGoods = $this->getGoodsByOrder($clientGoods);
        $this->buyerID = $buyerID;
    }

    // 根据订单信息获取真实的商品信息
    private function getGoodsByOrder($clientGoods)
    {
        $goodsID = [];
        foreach ($clientGoods as $v){
            array_push($goodsID, $v['goods_id']);
        }

        $serverGoods = Goods::all($goodsID);
        return $serverGoods;
    }
}