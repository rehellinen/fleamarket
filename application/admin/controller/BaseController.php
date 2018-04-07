<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 14:13
 */

namespace app\admin\controller;


use app\common\model\Image;
use think\Controller;
use think\Request;
use think\Session;
use app\common\model\Menu as MenuModel;

class BaseController extends Controller
{
    public function _initialize()
    {
        // 判断是否登录
        $res = Session::has('loginUser', 'admin');
        if(!$res){
            $this->redirect("admin/Login/index");
        }

        // 导航栏用户信息
        $seller = Session::get('loginUser', 'admin');
        $this->assign('user', $seller);

        // 导航栏信息获取
        $menu = $this->addChildMenuToParentMenu();
        $this->assign('navMenu', $menu);

        // 导航栏激活状态的完成
        $controller = strtolower(Request::instance()->controller());
        $this->assign('controller', $controller);
    }

    private function addChildMenuToParentMenu()
    {
        // 获取父菜单
        $menu = (new MenuModel)->getParentMenu(1)->toArray();
        $temp = $menu;
        // 获取子菜单
        $childMenu = (new MenuModel)->getChildMenu(1)->toArray();
        // 子菜单附加到父菜单上
        foreach ($childMenu as $key => $value){
            $parentID = $value['parent_id'];
            foreach ($menu as $k => $v){
                $menu[$k]['child'][0] = $temp[$k];
                if($v['id'] == $parentID){
                    array_push($menu[$k]['child'], $childMenu[$key]);
                }
            }
        }
        return $menu;
    }

    // 排序通用方法
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

    // 设置状态通用方法
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

    public function add()
    {
        $post = Request::instance()->post();
        if($post){
            // 判断是否上传了图片
            if(isset($post['image_id'])){
                $image = Image::create(['image_url' => $post['image_id']]);
                $post['image_id'] = $image->id;
            }
            $controller = Request::instance()->controller();
            $res = model($controller)->insert($post);
            if($res){
                return show(1,'新增成功');
            }else{
                return show(0,'新增失败');
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit()
    {
        $post = Request::instance()->post();
        if($post){
            // 判断是否上传了图片
            if(isset($post['image_id'])){
                $post['image_id'] = $this->processImageUrl($post['image_id']);
            }
            $controller = Request::instance()->controller();
            $result = model($controller)->where('id='.$post['id'])->update($post);
            if($result){
                return show(1,'更新成功');
            }else{
                return show(0,'更新失败');
            }

        }else{
            $id = $_GET['id'];
            $image = $_GET['image'];
            $controller = Request::instance()->controller();
            if($image){
                $result = model($controller)->with('imageId')->where('id', '=', $id)->find()->toArray();
                $result['image_id'] = $result['image_id']['image_url'];
            }else{
                $result = model($controller)->get($id);
            }
            return $this->fetch('', [
                'res' => $result
            ]);
        }
    }

    private function processImageUrl($imageUrl){
        // 对上传图片的处理
        $pattern = '{/upload.+}';
        preg_match($pattern, $imageUrl, $match);
        $imageUrl = $match[0];
        $image = Image::create(['image_url' => $imageUrl]);
        return $image->id;
    }
}