<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 15:22
 */

namespace app\common\exception;


class OldGoodsException extends BaseException
{
    public $httpCode = 404;
    public $status = 30000;
    public $message = '产品不存在';
}