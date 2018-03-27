<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 11:10
 */

namespace app\common\model;


use enum\StatusEnum;

class Theme extends BaseModel
{
    public function getImageIdAttr($value)
    {
        $value = Image::get($value);
        return $value['image_url'];
    }

    // 获取首页主题
    public function getIndexTheme()
    {
        $condition = [
            'status' => StatusEnum::Normal
        ];
        return $this->where($condition)->limit(4)->select();
    }
}