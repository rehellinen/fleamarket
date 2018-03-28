<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 21:11
 */

namespace app\common\service;


use app\common\exception\GoodsException;
use app\common\exception\SuccessMessage;
use app\common\model\Goods;

class Order
{
    // 客户端提交的订单中商品信息
    protected $orderGoods;
    // 真实的商品信息
    protected $dbGoods;
    // 买家ID
    protected $buyerID;

    // 主方法
    public function place($buyerID, $orderGoods)
    {
        $this->orderGoods = $orderGoods;
        $this->dbGoods = $this->getGoodsByOrder($orderGoods);
        $this->buyerID = $buyerID;
        $orderStatus = $this->getOrderStatus();
        if(!$orderStatus['pass']){
            $orderStatus['order_id'] = -1;
            throw new SuccessMessage([
                'message' => '创建订单失败',
                'data' => $orderStatus
            ]);
        }
        // 开始创建订单
        $orderSnap = $this->snapOrder($orderStatus);
    }

    // 生成订单快照
    private function snapOrder($orderStatus)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'goodsStatus' => [],
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $orderStatus['orderPrice'];
        $snap['totalCount'] = $orderStatus['totalCount'];
        $snap['goodsStatus'] = $orderStatus['goodsStatusArray'];
        $snap['snapName'] = $orderStatus['goodsStatusArray'][0]['name'];
        $snap['snapImg'] = $orderStatus['goodsStatusArray'][0]['image_id']['image_url'];
        if(count($this->dbGoods) > 1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    // 获取订单状态
    private function getOrderStatus()
    {
        $orderStatus = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'goodsStatusArray' => []
        ];

        foreach ($this->orderGoods as $orderGoods)
        {
            $goodsStatus = $this->getProductStatus($orderGoods['goods_id'], $orderGoods['count'], $this->dbGoods);
            if(!$goodsStatus['haveStock']){
                $orderStatus['pass'] = false;
            }
            $orderStatus['orderPrice'] += $goodsStatus['totalPrice'];
            $orderStatus['totalCount'] += $goodsStatus['count'];
            array_push($orderStatus['goodsStatusArray'], $goodsStatus);
        }

        return $orderStatus;
    }

    // 获取单个商品的状态
    private function getProductStatus($orderGoodsID, $orderGoodsCount, $products)
    {
        $goodsIndex = -1;
        $goodsStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        for($i = 0; $i < count($products); $i++)
        {
            if($orderGoodsID == $products[$i]['id']){
                $goodsIndex = $i;
            }
        }
        if($goodsIndex == -1){
            throw new GoodsException([
                'status' => 80001,
                'msg' => 'id为'.$orderGoodsID.'的商品不存在，订单创建失败'
            ]);
        }else{
            $product = $products[$goodsIndex];
            $goodsStatus['id'] = $orderGoodsID;
            $goodsStatus['count'] = $orderGoodsCount;
            $goodsStatus['name'] = $product['name'];
            $goodsStatus['totalPrice'] = $product['price'] * $orderGoodsCount;
            if($product['quantity'] - $orderGoodsCount >= 0){
                $goodsStatus['haveStock'] = true;
            }
        }
        return $goodsStatus;
    }

    // 根据订单信息获取真实的商品信息
    private function getGoodsByOrder($clientGoods)
    {
        $goodsID = [];
        foreach ($clientGoods as $v){
            array_push($goodsID, $v['goods_id']);
        }

        $serverGoods = Goods::all($goodsID)->toArray();
        return $serverGoods;
    }
}