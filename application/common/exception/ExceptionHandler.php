<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/12/18
 * Time: 23:10
 */

namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
    private $httpCode;
    private $message;
    private $status;

    public function render(Exception $e)
    {
        // 判断是否为自定义的异常
        if($e instanceof BaseException) {
            $this->httpCode = $e->httpCode;
            $this->message = $e->message;
            $this->status = $e->status;
        }else{
            // 若为调试模式则返回TP5的错误显示页面
            if(config('app_debug')) {
                return parent::render($e);
            }else{
                $this->httpCode = 500;
                $this->message = '服务器内部错误';
                $this->status = 99999;
            }
        }

        $url = Request::instance()->url();

        $result = [
            'status' => $this->status,
            'message' => $this->message,
            'data' => [
                'request_url' => $url
            ]
        ];

        return json($result, $this->httpCode);
    }
}