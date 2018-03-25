<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 11:10
 */

namespace app\common\model;


class Theme extends BaseModel
{
    public function getImageIdAttr($value)
    {
        $value = Image::get($value);
        return $value['image_url'];
    }

    public function getIndexTheme()
    {
        $condition = [
            'status' => 1
        ];
        return $this->where($condition)->limit(4)->select();
    }
}