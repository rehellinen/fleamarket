<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:55
 */

namespace app\common\model;


class Deal extends BaseModel
{
    // 获取订单数量
    public function getDealCount()
    {
        $data['status'] = 1;
        return $this->where($data)->count();
    }
}