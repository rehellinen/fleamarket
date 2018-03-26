<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 18:25
 */

namespace app\common\exception;


class ForbiddenException extends BaseException
{
    public $httpCode = 403;
    public $status = 10002;
    public $message = '请求的资源禁止访问';
}