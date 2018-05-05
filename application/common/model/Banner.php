<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:12
 */

namespace app\common\model;


use enum\StatusEnum;
use app\common\exception\BannerException;

class Banner extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    /**
     * 小程序获取轮播图的方法
     * @return mixed
     * @throws BannerException Banner不存在
     */
    public function getBanners()
    {
        $condition['status'] = StatusEnum::NORMAL;
        $maxCount = config('admin.max_banner_count');
        $banners = $this->where($condition)->order('listorder desc, id desc')
            ->with('imageId')->limit($maxCount)->select();

        if (!$banners) {
            throw new BannerException();
        }

        return $banners->hidden(['status', 'listorder', 'image_id' => ['status']]);
    }
}