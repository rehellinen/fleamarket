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
    // 获取订单数量
    public function getDealCount()
    {
        $data['status'] = StatusEnum::Normal;
        return $this->where($data)->count();
    }
}