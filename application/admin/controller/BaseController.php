<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:13
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Session;
use app\common\model\Menu as MenuModel;

class BaseController extends Controller
{
    public function _initialize()
    {
        //判断是否登录
        $res = Session::has('loginUser', 'admin');
        if(!$res){
            $this->redirect("admin/Login/index");
        }

        //导航栏用户信息
        $seller = Session::get('loginUser', 'admin');
        $this->assign('user', $seller);

        // 获取菜单信息
        $menu = (new MenuModel)->getNormal();
        $this->assign('menu', $menu);

        //导航栏激活状态的完成
        $controller = strtolower(Request::instance()->controller());
        $this->assign('controller', $controller);
    }

    public function listorder()
    {
        $post = Request::instance()->post();
        $controller = Request::instance()->controller();
        $id = $post['id'];
        $listorder = $post['listorder'];

        $res = model($controller)->updateListorder($id, $listorder);
        if($res){
            return show(1,'更新排序成功');
        }else{
            return show(0,'更新排序失败');
        }
    }

    public function setStatus()
    {
        $post = Request::instance()->post();
        $validate = validate('common');
        if(!$validate->scene('status')->check($post)){
            return show(0, $validate->getError());
        }

        $controller = Request::instance()->controller();
        $res = model($controller)->updateStatus($post['id'], $post['status']);
        if($res){
            return show(1,'更新成功');
        }else{
            return show(0,'更新失败');
        }
    }

    public function getLoginUser()
    {
        $module = Request::instance()->module();
        $lowerModule = strtolower($module);

        $user = Session::get('loginUser', $lowerModule);

        return $user;
    }
}