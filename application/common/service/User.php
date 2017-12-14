<?php
namespace app\common\service;
use think\Loader;
use think\Session;

/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/21
 * Time: 18:08
 */
class User
{
    private $oldPassword;
    private $newPassword;

    public function editPassword($data)
    {
        $this->oldPassword = $data['oldPassword'];
        $this->newPassword = $data['password'];

        // 校验密码是否正确
        $user = $this->compare($data['module']);
        if(!$user) {
            return false;
        }

        // 修改密码
        $newData['password'] = md5(config('admin.md5_prefix').$this->newPassword.$user['code']);
        $res = Loader::model($data['module'])->where('id='.$user['id'])->update($newData);

        if(!$res) {
            return false;
        } else {
            return true;
        }
    }

    // 校验密码是否正确
    private function compare($module)
    {
        $user = Session::get('loginSeller', $module);

        $oldMd5 = md5(config('admin.md5_prefix').$this->oldPassword.$user['code']);

        if($oldMd5 != $user['password']) {
            return false;
        }

        return $user;
    }
}