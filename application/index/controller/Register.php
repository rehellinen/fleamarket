<?php
namespace app\index\controller;

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
        $this->model = model('Buyer');
    }

    public function index()
    {
        return $this->fetch();
    }

    public function register()
    {
        $post = Request::instance()->post();
        $validate = validate('Buyer');
        if(!$validate->scene('register')->check($post)){
            return show(0, $validate->getError());
        }
        $res = $this->model->insertBuyer($post);
        if(!$res) {
            return show(0,'申请失败，请联系管理员');
        }else{
            return show(1,'申请成功！');
        }
    }

    public function wait()
    {
        //导航栏买家信息
        $res = Session::get('loginUser', 'buyer');
        $buyerId = $res['id'];
        $buyer = model('Buyer')->get(['id'=>$buyerId]);

        // 控制器
        $controller = Request::instance()->controller();

        return $this->fetch('', [
            'buyer' => $buyer,
            'controller' => $controller
        ]);
    }

}