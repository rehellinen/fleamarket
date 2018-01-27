<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/12/19
 * Time: 0:00
 */

namespace app\common\exception;


class ParameterException extends BaseException
{
    public $httpCode = 200;
    public $status = 90000;
    public $message = '参数错误';
}