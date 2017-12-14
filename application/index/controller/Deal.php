<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 20:28
 */

namespace app\index\controller;

use think\Request;
use think\Session;

class Deal extends Base
{
    public function index()
    {
        if(!Session::has('loginBuyer', 'index')){
            return show(0,'请登陆后再购买');
        }

        if($_POST){
            if(!Session::has('loginBuyer', 'index')){
                return show(0,'请登陆后再购买');
            }else{
                return show(1,'跳转成功');
            }
        }

        $get = Request::instance()->get();
        $id = 1;
        $goods = model('Goods')->get(['id'=>$id]);
        return $this->fetch('', [
            'goods' => $goods
        ]);
    }
}