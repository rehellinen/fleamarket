<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/26
 * Time: 12:06
 * Token 的基类
 */

namespace app\common\service;


class Token
{
    public static function generateToken()
    {
        // 32位字符串
        $randChars = getRandChar(32);
        // 时间戳
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 盐
        $salt = config('admin.md5_prefix');

        $token = md5($salt.$randChars.$timeStamp);
        return $token;
    }
}