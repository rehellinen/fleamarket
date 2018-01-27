<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/26
 * Time: 12:06
 * Token 的基类
 */

namespace app\common\service;


use app\common\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

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

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);

        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)){
                $var = $vars[$key];
                return $var;
            }else{
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }

    public static function getIDByToken()
    {
        $buyerID = self::getCurrentTokenVar('buyerID');
        return $buyerID;
    }
}