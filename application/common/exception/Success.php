<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/20
 * Time: 6:23
 */

namespace app\common\exception;


class Success extends BaseException
{
    public $httpCode = 200;
    public $status = 90000;
    public $message = '成功！';
    public $data = [];
}