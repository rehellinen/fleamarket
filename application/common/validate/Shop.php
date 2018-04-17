<?php
namespace app\common\validate;

use think\Validate;


/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 16:47
 */
class Shop extends BaseValidate
{
    protected $rule = [
        ['name', 'require|chsAlpha', '名字不能为空|名字不合法'],
        ['telephone', 'require|number|max:11', '电话不能为空|电话必须为数字|电话长度不合法'],
        ['email', 'require|email', '邮箱不能为空|email格式不合法'],
        ['weixin', 'require', '微信号不能为空'],
        ['zhifubao', 'require', '支付宝账号不能为空'],
        ['dormitory', 'require|number|max:4', '宿舍号不能为空|宿舍号必须为数字|宿舍号长度不合法'],
        ['oldPassword', 'require|isNotEmpty', '原密码不能为空|原密码不能为空'],
        ['password', 'require', '密码不能为空'],
        ['number', 'require', '学号不能为空'],
        ['code', 'require', 'code不能为空'],
        ['major', 'require', '主营不能为空'],
    ];

    protected $scene = [
        'register'  => ['name', 'number', 'telephone', 'weixin', 'dormitory', 'email', 'zhifubao', 'code', 'major'],
        'edit'  => ['name', 'telephone', 'email', 'weixin', 'zhifubao', 'dormitory'],
        'password' => ['oldPassword', 'password']
    ];
}