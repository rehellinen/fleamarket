<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/12
 * Time: 16:12
 */

namespace app\common\exception;


class ShopException extends BaseException
{
    public $httpCode = 404;
    public $status = 90000;
    public $message = '自营店家不存在';
    public $data = [];
}