<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 17:12
 */

namespace app\common\exception;


class BuyerException extends BaseException
{
    public $httpCode = 404;
    public $status = 60000;
    public $message = '用户不存在';
    public $data = [];
}