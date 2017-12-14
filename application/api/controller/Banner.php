<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/23
 * Time: 0:00
 */

namespace app\api\controller;


use think\Controller;
use think\Request;

class Banner extends Controller
{
    public function add()
    {
        $post = Request::instance()->post();

        $res = model('Banner')->save($post);
        if($res) {
            return show(1,'添加轮播图成功');
        } else {
            return show(0, '添加轮播图失败');
        }
    }
}