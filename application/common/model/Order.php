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

    public function snapItems()
    {
        return $this->hasMany('orderGoods', 'order_id', 'id');
    }

    // 获取订单数量
    public function getDealCount()
    {
        $data['status'] = StatusEnum::Normal;
        return $this->where($data)->count();
    }

    public static function getOrderByUser($buyerID, $page, $size)
    {
        $res = self::where('buyer_id', '=', $buyerID)->order('create_time desc')
        ->paginate($size, true, [
            'page' => $page
        ]);
        return $res;
    }

    public function getOrderByBuyerID($id, $buyerID, $type){
        if($type == 1){
            $str = 'shop';
        }else{
            $str = 'seller';
        }
        $order =  $this->where([
            'buyer_id' => $buyerID,
            'id' => $id
        ])->with(['snapItems' => function($query){
            $query->with('imageId');
        }])->with($str)->find()->hidden([
            $str => ['is_root', 'listorder', 'status', 'number', 'open_id']
        ]);
        return $order;
    }
}