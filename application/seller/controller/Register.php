<?php
namespace app\seller\controller;


use phpmailer\EmailTo;
use think\Controller;
use think\Request;
use think\Session;

/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 16:58
 */
class Register extends Controller
{
    private $model;

    public function _initialize()
    {
        $this->model = model('Seller');
    }

    public function index()
    {
        return $this->fetch();
    }

    public function register()
    {
        $post = Request::instance()->post();
        $validate = validate('Seller');
        if(!$validate->scene('register')->check($post)){
            return show(0, $validate->getError());
        }
        $res = $this->model->insertSeller($post);
        if(!$res) {
            return show(0,'申请失败，请联系管理员');
        }else{
            return show(1,'申请成功！请耐心等候管理员审核', ['name' => $post['name']]);
        }
    }

    public function wait()
    {
        //导航栏买家信息
        $res = Session::get('loginUser', 'seller');
        $sellerId = $res['id'];
        $buyer = model('Seller')->get(['id'=>$sellerId]);


        return $this->fetch('', [
            'user' => $buyer,
            'controller' => 'register'
        ]);
    }

}