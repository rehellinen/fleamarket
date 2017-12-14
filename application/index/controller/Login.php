<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 23:15
 */

namespace app\index\controller;


use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller
{
    private $model;

    public function _initialize()
    {
        $this->model = model('Buyer');
    }

    public function index()
    {
        return $this->fetch();
    }

    public function check()
    {
        $post = Request::instance()->post();
        $validate = validate('Buyer');
        if(!$validate->scene("login")->check($post)){
            return show(0,$validate->getError());
        }
        $tele = $post['tele'];

        $res = $this->model->getBuyerByTele($tele);
        if(!$res){
            return show(0,'该用户不存在');
        }else{
            $md5pwd = md5(config('admin.md5_prefix').$post['password'].$res['code']);
            if($md5pwd === $res['password']){
                Session::set('loginUser', $res, 'buyer');
                return show(1,'登录成功');
            }else{
                return show(0,'密码错误');
            }
        }
    }

    public function logOut()
    {
        Session::set('loginUser',null, 'buyer');
        $this->redirect('index/login/index');
    }
}