<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:36
 */

namespace app\common\model;


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
}