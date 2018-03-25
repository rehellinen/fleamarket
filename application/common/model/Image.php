<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 15:38
 */

namespace app\common\model;


class Image extends BaseModel
{
    public function getImageUrlAttr($value){
        $value = config('photo_url_prefix').$value;
        $value = str_replace('\\', '/', $value);
        return $value;

    }
}