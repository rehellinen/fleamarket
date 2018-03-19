<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/19
 * Time: 18:49
 */

namespace app\common\exception;


class BannerException extends BaseException
{
    public $httpCode = 200;
    public $status = 20000;
    public $message = '没有轮播图';
}