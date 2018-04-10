<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 17:25
 */

namespace app\common\model;


use enum\StatusEnum;

class Buyer extends BaseModel
{
    // 获取买家数量
    public function getBuyerCount()
    {
        $data['status'] = StatusEnum::Normal;
        return $this->where($data)->count();
    }
}