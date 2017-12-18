<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/12/19
 * Time: 0:00
 */

namespace app\common\exception;


class Parameter extends BaseException
{
    public $httpCode = 400;
    public $status = 10000;
    public $message = '参数错误';
}