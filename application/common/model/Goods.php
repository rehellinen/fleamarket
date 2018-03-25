<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:49
 */

namespace app\common\model;


class Goods extends BaseModel
{
    // status值
    // -1->被删除, 0->审核不通过, 1->正常, 2->卖出
    public function getImageIdAttr($value)
    {
        $value = Image::get($value);
        return $value['image_url'];
    }

    public function getShopIdAttr($value)
    {
        $value = Shop::get($value);
        return $value['name'];
    }

    public function getSellerGoods($sellerId)
    {
        $condition['status'] = 1;
        $condition['seller_id'] = $sellerId;
        return $this->where($condition)->order('listorder desc, id desc')->paginate(13);
    }
}