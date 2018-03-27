<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:36
 */

namespace app\common\model;


use enum\StatusEnum;

class OldGoods extends BaseModel
{
    public function getImageIdAttr($value)
    {
        $value = Image::get($value);
        return $value['image_url'];
    }

    public function getSellerIdAttr($value)
    {
        $value = Seller::get($value);
        return $value['name'];
    }

    // 根据卖家id获取所有二手商品
    public function getSellerGoods($sellerId)
    {
        $condition['status'] = StatusEnum::Normal;
        $condition['seller_id'] = $sellerId;
        return $this->where($condition)->order('listorder desc, id desc')->paginate(13);
    }
}