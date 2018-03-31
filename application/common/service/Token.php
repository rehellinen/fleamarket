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
use app\common\exception\SellerException;

class Token
{
    // 生成随机字符串作为令牌
    public static function generateToken()
    {
        // 32位字符串
        $randChars = getRandChars(32);
        // 时间戳
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 前缀
        $prefix = config('admin.md5_prefix');

        $token = md5($prefix.$randChars.$timeStamp);
        return $token;
    }

    // 根据Token令牌获取对应的信息
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

    // 根据买家ID获取相应的信息
    public static function getBuyerID()
    {
        $buyerID = self::getCurrentTokenVar('buyerID');
        return $buyerID;
    }

    // 检查令牌是否过期
    public static function verifyToken($token)
    {
        $isExisted = Cache::get($token);
        if($isExisted){
            return true;
        }else{
            return false;
        }
    }

    // 用于校对商户输入的密码和数据库中的密码
    public static function checkPassword($password, $seller)
    {
        // md5加密的前缀
        $prefix = config('admin.md5_prefix');
        // 盐
        $salt = $seller->code;
        // 商户输入的加密后的密码
        $md5Password = md5($prefix.$password.$salt);

        if($md5Password === $seller->password){
            return true;
        }else{
            throw new SellerException([
                'message' => '输入的密码不正确',
                'status'=> '70001'
            ]);
        }
    }

    public static function isValidOperate($checkedBuyerID)
    {
        $currentBuyerID = self::getBuyerID();
        if($currentBuyerID == $checkedBuyerID){
            return true;
        }else{
            throw new TokenException([
                'message' => '订单与用户不匹配',
                'errorCode' => 80002
            ]);
        }
    }
}