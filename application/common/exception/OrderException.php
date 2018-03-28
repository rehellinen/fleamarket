<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/28
 * Time: 18:37
 */

namespace app\common\exception;


class OrderException extends BaseException
{
    public $httpCode = 404;
    public $status = 80000;
    public $message = '订单不存在';
}