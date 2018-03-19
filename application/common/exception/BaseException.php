<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/12/18
 * Time: 23:32
 */

namespace app\common\exception;


use think\Exception;

class BaseException extends Exception
{
    // HTTP 错误码
    public $httpCode = 500;

    // 具体的错误码参照根目录下的error_code
    public $status = 10000;

    // 返回的错误信息
    public $message = '参数错误';

    // 返回的数据
    public $data = [];

    public function __construct($setting = [])
    {
        if(array_key_exists('httpCode', $setting)) {
            $this->httpCode = $setting['httpCode'];
        }

        if(array_key_exists('status', $setting)) {
            $this->status = $setting['status'];
        }

        if(array_key_exists('message', $setting)) {
            $this->message = $setting['message'];
        }

        if(array_key_exists('data', $setting)) {
            $this->data['data'] = $setting['data'];
        }
    }
}