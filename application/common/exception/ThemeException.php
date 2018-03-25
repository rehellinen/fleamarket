<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 19:29
 */

namespace app\common\exception;


class ThemeException extends BaseException
{
    public $httpCode = 404;
    public $status = 70000;
    public $message = '主题不存在';
}