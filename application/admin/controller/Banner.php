<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:01
 */

namespace app\admin\controller;


class Banner extends BaseController
{
    public function index()
    {
        $banner = model('Banner')->getAll();
        return $this->fetch('', [
            'banner' => $banner
        ]);
    }

    public function add()
    {
        return $this->fetch();
    }
}