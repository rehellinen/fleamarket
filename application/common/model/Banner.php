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
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    // 小程序获取轮播图的方法
    public function getBanners()
    {
        $condition['status'] = StatusEnum::Normal;
        $maxCount = config('admin.max_banner_count');
        return $this->where($condition)->order('listorder desc, id desc')
                ->with('imageId')->limit($maxCount)->select();
    }
}