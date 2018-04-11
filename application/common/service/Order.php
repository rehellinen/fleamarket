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
use app\common\model\Image;
use app\common\model\OrderGoods;
use enum\StatusEnum;
use think\Db;
use think\Exception;

class Order
{
    // 客户端提交的订单中商品
    protected $orderGoods;
    // 真实的商品信息
    protected $dbGoods;
    // 买家ID
    protected $buyerID;
    // 订单号
    protected $orderNo;

    /**
     * Order constructor.
     * @param int $buyerID 买家ID
     * @param array $orderGoods 订单中的商品数组
     */
    public function __construct()
    {
        $this->buyerID = Token::getBuyerID();
        $this->orderNo = self::makeOrderNo();
    }

    /**
     * 下单主方法
     * @return array
     */
    public function place($orderGoods)
    {
        $this->orderGoods = $orderGoods;
        $this->dbGoods = $this->getGoodsByOrder($orderGoods);
        $order = [
            'pass' => false,
            'orderPrice' => 0,
            'totalCount' => 0,
            'order_no' => '',
            'singleOrder' => []
        ];
        foreach ($this->dbGoods as $goodsArr){
            // 获取订单状态
            $orderStatus = $this->getOrderStatus($goodsArr);
            // 生成订单快照
            $snap = $this->snapOrder($orderStatus);
            // 生成订单
            $this->createOrder($snap, $orderStatus['goodsStatusArray']);
            $orderStatus['pass'] = true;
            $order['pass'] = true;
            $order['orderPrice'] += $orderStatus['orderPrice'];
            $order['totalCount'] += $orderStatus['totalCount'];
            $order['order_no'] = $this->orderNo;
            array_push($order['singleOrder'], $orderStatus);
        }


        return $order;
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

        $dbGoods = (new Goods())->where([
            'id' => ['in', $goodsID],
            'status' => StatusEnum::Normal
        ])->select()->toArray();

        $belongsTo = [];

        foreach ($dbGoods as $value){
            $key = $value['type'] . '-' . $value['foreign_id'];
            if(!array_key_exists($key, $belongsTo)){
                $belongsTo[$key] = [];
            }
            array_push($belongsTo[$key], $value);
        }

        return $belongsTo;
    }

    /**
     * 获取订单的状态
     * @return array 订单的状态
     * @throws SuccessMessage 返回订单的信息
     *  pass -> 订单商品是否所有都通过了库存量检测
     *  orderPrice -> 订单的总价格
     *  totalCount -> 订单中商品的总数量
     *  goodsStatusArray -> 订单中商品的状态
     */
    private function getOrderStatus($goodsArr)
    {
        $orderStatus = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'goodsStatusArray' => []
        ];

        foreach ($goodsArr as $goods)
        {
            $goodsStatus = $this->getGoodsStatus($goods);
            if(!$goodsStatus['haveStock']){
                $orderStatus['pass'] = false;
            }
            $orderStatus['orderPrice'] += $goodsStatus['totalPrice'];
            $orderStatus['totalCount'] += $goodsStatus['count'];
            array_push($orderStatus['goodsStatusArray'], $goodsStatus);
        }
        if(!$orderStatus['pass']){
            $orderStatus['order_id'] = -1;
            throw new SuccessMessage([
                'message' => '创建订单失败',
                'data' => $orderStatus
            ]);
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
     *  price -> 单个商品的价格
     *  image -> 商品图片
     */
    private function getGoodsStatus($goods)
    {
        $goodsIndex = -1;
        $count = 0;
        for($i = 0; $i < count($this->orderGoods); $i++){
            if($goods['id'] == $this->orderGoods[$i]['goods_id']){
                $goodsIndex = $i;
                $count = $this->orderGoods[$i]['count'];
            }
        }

        if($goodsIndex == -1){
            throw new GoodsException([
                'status' => 80001,
                'message' => 'id为'.$goods['id'].'的商品不存在，订单创建失败'
            ]);
        }else{
            $goodsStatus = [
                'goods_id' => $goods['id'],
                'foreign_id' => $goods['foreign_id'],
                'type' => $goods['type'],
                'name' => $goods['name'],
                'price' => $goods['price'],
                'count' => $count,
                'totalPrice' => $goods['price'] * $count,
                'haveStock' => false,
                'image_id' => $goods['image_id']
            ];

            if($goods['quantity'] - $count >= 0){
                $goodsStatus['haveStock'] = true;
            }
        }
        return $goodsStatus;
    }

    /**
     * 生成订单快照
     * @param array $orderStatus 订单的状态
     * @return array
     *  orderPrice -> 订单总价格
     *  totalCount -> 订单商品总数量
     *  snapName -> 订单中第一个商品的名称
     *  snapImg -> 订单中第一个商品的图片
     */
    private function snapOrder($orderStatus)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'snapName' => '',
            'snapImg' => ''
        ];
        $imageUrl = (new Image())->get($orderStatus['goodsStatusArray'][0]['image_id'])->image_url;
        $snap['orderPrice'] = $orderStatus['orderPrice'];
        $snap['totalCount'] = $orderStatus['totalCount'];
        $snap['snapName'] = $orderStatus['goodsStatusArray'][0]['name'];
        $snap['snapImg'] = $imageUrl;
        if(count($orderStatus['goodsStatusArray']) > 1){
            $snap['snapName'] .= ' 等';
        }
        return $snap;
    }

    /**
     * 生成订单
     * @param array $snap 订单快照
     * @throws Exception 多表插入若失败则抛出TP5异常
     *  order_no -> 订单号
     *  order_id -> 订单的ID
     *  create_time -> 订单的创建时间
     */
    private function createOrder($snap, $goodsArray)
    {
        Db::startTrans();
        try{
            // 订单表的插入
            $order = new \app\common\model\Order();
            $order->data([
                'buyer_id' => $this->buyerID,
                'order_no' => $this->orderNo,
                'total_price' => $snap['orderPrice'],
                'total_count' => $snap['totalCount'],
                'snap_img' => $snap['snapImg'],
                'snap_name' => $snap['snapName']
            ]);
            $order->save();
            $orderID = $order->id;

            // 订单---商品表的插入
            foreach ($goodsArray as &$v){
                $v['order_id'] = $orderID;
            }

            $orderProduct = new OrderGoods();
            $orderProduct->saveAll($goodsArray);
            Db::commit();
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
     * 检查订单中商品库存量
     * @param array $orderID 订单ID
     * @return array 订单状态
     */
    public function checkStock($orderIDs)
    {
        $orderGoods = (new OrderGoods())->where([
            'order_id' => ['in', $orderIDs]
        ])->select()->toArray();
        $this->orderGoods = $orderGoods;
        $this->dbGoods = $this->getGoodsByOrder($orderGoods);

        $order = [
            'pass' => true,
            'singleOrder' => [],
            'orderPrice' => 0
        ];

        foreach ($this->dbGoods as $goodsArr){
            // 获取订单状态
            $orderStatus = $this->getOrderStatus($goodsArr);
            if(!$orderStatus['pass']){
                $order['pass'] = false;
            }
            $order['orderPrice'] += $orderStatus['orderPrice'];
            array_push($order['singleOrder'], $orderStatus);
        }

        // 没有通过库存量检测时的操作
        if(!$order['pass']){
            throw new GoodsException([
                'message' => '库存量不足',
                'status' => 30001
            ]);
        }

        return $order;
    }
}