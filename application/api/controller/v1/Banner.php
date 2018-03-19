<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:00
 */

namespace app\api\controller\v1;


use app\common\exception\BannerException;
use app\common\exception\SuccessException;
use app\common\model\Banner as BannerModel;

class Banner extends BaseController
{
    public function getBanner()
    {
        $banners = (new BannerModel())->getBanners();
        if(!$banners){
            throw new BannerException();
        }
        throw new SuccessException([
            'message' => '获取轮播图成功',
            'data' => $banners
        ]);
    }
}