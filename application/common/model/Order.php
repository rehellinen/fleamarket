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

    public function getSnapItemsAttr($value)
    {
        if(empty($value)){
            return null;
        }else{
            return json_decode($value);
        }
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
}