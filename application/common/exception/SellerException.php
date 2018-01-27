<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 23:44
 */

namespace app\common\exception;


class SellerException extends BaseException
{
    public $httpCode = 401;
    public $status = 70000;
    public $message = '商户不存在';
    public $data = [];
}