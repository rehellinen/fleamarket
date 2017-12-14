<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:18
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function check()
    {
        $post = Request::instance()->post();
        $validate = validate('Seller');
        if(!$validate->scene('login')->check($post)){
            return show(0,$validate->getError());
        }
        $tele = $post['tele'];
        $password = $post['password'];
        $seller = model('Seller')->getRootByTele($tele);
        if(!$seller || $seller['status']!=1 || $seller['is_root']!=1){
            return show(0,'该用户不存在');
        }
        $password = md5(config('admin.md5_prefix').$password.$seller['code']);
        if($seller['password']!=$password){
            return show(0,'密码不正确');
        }else{
            Session::set('loginUser', $seller, 'admin');
            return show(1,'登录成功');
        }
    }

    public function logOut()
    {
        Session::set('loginUser', null, 'admin');
        $this->redirect('admin/login/index');
    }
}