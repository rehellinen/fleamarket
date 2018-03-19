<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/19
 * Time: 18:49
 */

namespace app\common\exception;


class GoodsException extends BaseException
{
    public $httpCode = 200;
    public $status = 30000;
    public $message = '产品不存在';
}