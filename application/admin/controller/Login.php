<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:18
 */

namespace app\admin\controller;


use app\common\model\Admin;
use enum\StatusEnum;
use think\Controller;
use think\Request;
use think\Session;
use app\common\validate\Seller as SellerValidate;
use app\common\model\Seller as SellerModel;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function check()
    {
        // 验证与获取数据
        $post = Request::instance()->post();
        $validate = (new SellerValidate);
        if(!$validate->scene('loginCMS')->check($post)){
            return show(0,$validate->getError());
        }
        $data = $validate->getDataByScene('loginCMS');

        $admin = (new Admin())->getByAccount($data['account']);
        if(!$admin){
            return show(0,'该用户不存在');
        }

        $inputMd5Password = md5(config('admin.md5_prefix').$data['password'].$admin['salt']);
        if($admin['password']!=$inputMd5Password){
            return show(0,'密码不正确');
        }else{
            Session::set('loginUser', $admin, 'admin');
            return show(1,'登录成功');
        }
    }

    public function logOut()
    {
        Session::set('loginUser', null, 'admin');
        $this->redirect('admin/login/index');
    }
}