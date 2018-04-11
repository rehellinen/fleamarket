<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/29
 * Time: 10:18
 */

namespace app\common\model;


class OrderGoods extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }
}