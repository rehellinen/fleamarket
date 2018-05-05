<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:55
 */

namespace app\common\model;


use enum\StatusEnum;

class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function shop()
    {
        return $this->belongsTo('Shop', 'foreign_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('Seller', 'foreign_id', 'id');
    }

    public function buyerId()
    {
        return $this->belongsTo('Buyer', 'buyer_id', 'id');
    }

    public function snapItems()
    {
        return $this->hasMany('orderGoods', 'order_id', 'id');
    }

    // 获取订单数量
    public function getDealCount()
    {
        $data['status'] = StatusEnum::NORMAL;
        return $this->where($data)->count();
    }

    public static function getOrderByUser($buyerID, $status, $page, $size)
    {
        $condition = [
            'buyer_id' => $buyerID,
            'status' => ['in', $status]
        ];
        $res = self::where($condition)->order('create_time desc')
        ->paginate($size, true, [
            'page' => $page
        ])->hidden(['snap_items', 'prepay_id']);
        return $res;
    }

    public function getOrderBySellerOrShop($type, $status, $uid, $page, $size)
    {
        return $this->order('id desc')->where([
            'foreign_id' => $uid,
            'type' => $type,
            'status' => ['in', $status]
        ])->paginate($size, true, [
            'page' => $page
        ])->hidden(['snap_items', 'prepay_id', 'listorder']);
    }

    public function getBuyerOrderByID($id, $buyerID, $type){
        if($type == 1){
            $str = 'shop';
        }else{
            $str = 'seller';
        }
        $order =  $this->where([
            'buyer_id' => $buyerID,
            'id' => $id
        ])->order('id desc')->with(['snapItems' => function($query){
            $query->with('imageId');
        }])->with($str)->find()->hidden([
            $str => ['is_root', 'listorder', 'status', 'number', 'open_id']
        ]);
        return $order;
    }

    public function getSellerOrShopDetailByID($id, $uid, $type)
    {
        $order =  $this->where([
            'foreign_id' => $uid,
            'type' => $type,
            'id' => $id
        ])->order('id desc')->with(['snapItems' => function($query){
            $query->with('imageId');
        }])->with('buyerId')->find()->hidden([
            'buyerId' => ['listorder', 'status', 'number', 'open_id']
        ]);

        return $order;
    }

    public function updateOrderStatus($id, $uid, $type, $status)
    {
        return $this->where([
            'type' => $type,
            'id' => $id,
            'foreign_id' => $uid
        ])->update(['status' => $status]);
    }
}