<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 15:34
 */

namespace app\index\controller;


use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{
    public function _initialize()
    {
        // 导航栏买家信息
        $res = Session::get('loginUser', 'buyer');
        $buyerId = $res['id'];
        $buyer = model('Buyer')->get(['id'=>$buyerId]);

        // 获取控制器
        $controller = Request::instance()->controller();

        $this->assign('buyer', $buyer);
        $this->assign('controller', $controller);
    }
}