<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:46
 */

namespace app\common\model;


class Shop extends BaseModel
{
    public function getTopImageAttr($value)
    {
        $value = config('photo_url_prefix').$value;
        $value = str_replace('\\', '/', $value);
        return $value;
    }
}