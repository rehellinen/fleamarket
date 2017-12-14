<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 10:52
 */

namespace app\api\controller;

use app\common\service\User as UserService;
use think\Controller;
use think\Loader;
use think\Request;
use think\Session;

class User extends Controller
{
    public function updateUser()
    {
        $post = Request::instance()->post();

        // 获取当前模块
        $module = $post['module'];

        $validate = validate($module);
        if(!$validate->scene('edit')->check($post)){
            return show(0,$validate->getError());
        }

        $seller = Session::get('loginUser', $module);

        $id = $seller['id'];
        unset($post['id']);
        unset($post['module']);
        $res = model($module)->updateById($id, $post);
        if($res){
            return show(1,'更新成功');
        }else{
            return show(0,'更新失败');
        }
    }

    public function editPassword()
    {
        $post = Request::instance()->post();

        // 获取当前模块
        $module = $post['module'];

        $validate = validate($module);
        if(!$validate->scene('password')->check($post)){
            return show(0,$validate->getError());
        }


        // 获取当前用户信息
        $user = Session::get('loginUser', $module);
        $uid = $user['id'];
        $userDB = Loader::model($module)->get($uid);

        // 对比旧密码
        $oldMd5 = md5(config('admin.md5_prefix').$post['oldPassword'].$userDB['code']);

        if($oldMd5 != $userDB['password']) {
            return show(0, '密码错误');
        }

        // 修改密码
        $newData['password'] = md5(config('admin.md5_prefix').$post['password'].$userDB['code']);
        $res = Loader::model($module)->where('id='.$userDB['id'])->update($newData);

        if($res) {
            return show(1,'修改密码成功');
        } else {
            return show(0,'修改密码失败');
        }
    }
}