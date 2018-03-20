<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/26
 * Time: 14:41
 */

namespace app\common\exception;


class TokenException extends BaseException
{
    public $httpCode = 401;
    public $status = 50000;
    public $message = 'Token无效或已过期';
}