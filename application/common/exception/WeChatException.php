<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/25
 * Time: 22:19
 */

namespace app\common\exception;


class WeChatException extends BaseException
{
    public $httpCode = 400;
    public $status = 10004;
    public $message = '微信登陆接口发生错误';
}