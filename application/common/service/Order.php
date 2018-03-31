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
use app\common\model\OrderGoods;
use think\Db;
use think\Exception;

class Order
{
    // 客户端提交的订单中商品信息
    protected $orderGoods;
    // 真实的商品信息
    protected $dbGoods;
    // 买家ID
    protected $buyerID;

    /**
     * 下单主方法
     * @param int $buyerID 买家ID
     * @param array $orderGoods 订单
     * @throws SuccessMessage
     * @return array
     */
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
        // 生成订单快照
        $orderSnap = $this->snapOrder($orderStatus);
        // 生成订单
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return array_merge($orderStatus, $order);
    }

    /**
     * 检查订单中商品库存量
     * @param int $orderID 订单ID
     * @return array 订单状态
     */
    public function checkStock($orderID)
    {
        $orderGoods = (new OrderGoods())->where('order_id=' . $orderID)->select()->toArray();
        $this->orderGoods = $orderGoods;
        $this->dbGoods = $this->getGoodsByOrder($orderGoods);

        $orderStatus = $this->getOrderStatus();
        return $orderGoods;
    }

    /**
     * 生成订单
     * @param array $snap 订单快照
     * @throws Exception 多表插入若失败则抛出TP5异常
     * @return array 订单的相关信息
     *  order_no -> 订单号
     *  order_id -> 订单的ID
     *  create_time -> 订单的创建时间
     */
    private function createOrder($snap)
    {
        Db::startTrans();
        try{
            $orderNo = self::makeOrderNo();
            $order = new \app\common\model\Order();
            $order->data([
                'buyer_id' => $this->buyerID,
                'order_no' => $orderNo,
                'total_price' => $snap['orderPrice'],
                'total_count' => $snap['totalCount'],
                'snap_img' => $snap['snapImg'],
                'snap_name' => $snap['snapName'],
                'snap_items' => json_encode($snap['goodsStatus'])
            ]);
            $order->save();
            $orderID = $order->id;
            $createTime = $order->create_time;

            foreach ($this->orderGoods as &$v){
                $v['order_id'] = $orderID;
            }
            $orderProduct = new OrderGoods();
            $orderProduct->saveAll($this->orderGoods);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $createTime
            ];
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 生成订单号码
     * @return string 订单号
     */
    public static function makeOrderNo()
    {
        $yearCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yearCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) .
            date('d') . substr(time(), -5) . substr(microtime(), 2, 5) .
            sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 生成订单快照
     * @param array $orderStatus 订单的状态
     * @return array
     *  orderPrice -> 订单总价格
     *  totalCount -> 订单商品总数量
     *  goodsStatus -> 订单中商品的详情
     *  snapName -> 订单中第一个商品的名称
     *  snapImg -> 订单中第一个商品的图片
     */
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
        $snap['snapName'] = $this->dbGoods[0]['name'];
        $snap['snapImg'] = $this->dbGoods[0]['image_id']['image_url'];
        if(count($this->dbGoods) > 1){
            $snap['snapName'] .= ' 等';
        }
        return $snap;
    }

    /**
     * 获取订单的状态
     * @return array 订单的状态
     *  pass -> 订单商品是否所有都通过了库存量检测
     *  orderPrice -> 订单的总价格
     *  totalCount -> 订单中商品的总数量
     *  goodsStatusArray -> 订单中商品的状态
     */
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

    /**
     * 获取单个商品的状态
     * @param int $orderGoodsID 订单中单个商品的ID
     * @param int $orderGoodsCount 订单中该商品的数量
     * @param array $dbGoods 根据订单中所有商品ID在数据库中查找的结果
     * @throws GoodsException 商品不存在
     * @return array 单个商品的状态
     *  id -> 商品的id
     *  haveStock -> 商品是否有库存
     *  count -> 下单的商品数量
     *  name -> 商品的名称
     *  totalPrice -> 该商品的总价格
     */
    private function getProductStatus($orderGoodsID, $orderGoodsCount, $dbGoods)
    {
        $goodsIndex = -1;
        $goodsStatus = [
            'id' => null,
            'name' => '',
            'count' => 0,
            'totalPrice' => 0,
            'haveStock' => false
        ];
        for($i = 0; $i < count($dbGoods); $i++)
        {
            if($orderGoodsID == $dbGoods[$i]['id']){
                $goodsIndex = $i;
            }
        }
        if($goodsIndex == -1){
            throw new GoodsException([
                'status' => 80001,
                'msg' => 'id为'.$orderGoodsID.'的商品不存在，订单创建失败'
            ]);
        }else{
            $goods = $dbGoods[$goodsIndex];
            $goodsStatus['id'] = $orderGoodsID;
            $goodsStatus['name'] = $goods['name'];
            $goodsStatus['count'] = $orderGoodsCount;
            $goodsStatus['totalPrice'] = $goods['price'] * $orderGoodsCount;
            if($goods['quantity'] - $orderGoodsCount >= 0){
                $goodsStatus['haveStock'] = true;
            }
        }
        return $goodsStatus;
    }

    /**
     * 根据订单在数据库查找相关商品
     * @param array $orderGoods 订单中的商品
     * @return array 根据订单中的商品ID在数据库查出的集合
     */
    private function getGoodsByOrder($orderGoods)
    {
        $goodsID = [];
        foreach ($orderGoods as $v){
            array_push($goodsID, $v['goods_id']);
        }

        $dbGoods = (new Goods())->where('id', 'in', $goodsID)->with('imageId')->select()->toArray();
        return $dbGoods;
    }
}