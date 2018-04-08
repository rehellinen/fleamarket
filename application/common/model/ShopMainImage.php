<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/8
 * Time: 10:50
 */

namespace app\common\model;


class ShopMainImage extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }
}