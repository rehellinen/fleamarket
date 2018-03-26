<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 17:25
 */

namespace app\common\model;


class Buyer extends BaseModel
{
    public function address()
    {
        return $this->hasOne('BuyerAddress', 'buyer_id', 'id');
    }

    // 根据open_id获取用户信息
    public function getByOpenID($openid)
    {
        return $this->where(['open_id' => $openid])->find();
    }

    // 获取买家数量
    public function getBuyerCount()
    {
        $data['status'] = 1;
        return $this->where($data)->count();
    }
}