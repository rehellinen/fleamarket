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
        $banner = (new BannerModel())->getHasImage();
        return $this->fetch('', [
            'banner' => $banner
        ]);
    }
}