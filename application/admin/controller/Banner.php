<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:01
 */

namespace app\admin\controller;

use app\common\model\Banner as BannerModel;

class Banner extends BaseController
{
    public function index()
    {
        $banner = (new BannerModel())->getAdminBanner()->toArray();
        foreach ($banner as $key => $value) {
            $banner[$key]['image_id'] = $value['image_id']['image_url'];
        }
        return $this->fetch('', [
            'banner' => $banner
        ]);
    }
}