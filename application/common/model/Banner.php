<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:12
 */

namespace app\common\model;


use enum\StatusEnum;

class Banner extends BaseModel
{
    // 读取器设置图片url前缀
    public function getImageIdAttr($value)
    {
        $image = Image::get($value);
        return $image['image_url'];
    }

    // 小程序获取轮播图的方法
    public function getBanners()
    {
        $condition['status'] = StatusEnum::Normal;
        $maxCount = config('admin.max_banner_count');
        return $this->where($condition)->order('listorder desc, id desc')
                ->limit($maxCount)->select();
    }
}