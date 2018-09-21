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
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $httpCode;
    private $message;
    private $status;
    private $data;

    public function render(Exception $e)
    {
        // 判断是否为自定义的异常
        if($e instanceof BaseException) {
            $this->httpCode = $e->httpCode;
            $this->message = $e->message;
            $this->status = $e->status;
            $this->data = $e->data;
        }else{
            // 若为调试模式则返回TP5的错误显示页面
            if(config('app_debug')) {
                // 记录日志
                $this->log($e);
                return parent::render($e);
            }else{
                $this->httpCode = 500;
                $this->message = '服务器内部错误';
                $this->status = 99999;
                $this->data = [];
                // 记录日志
                $this->log($e);
            }
        }

        $url = Request::instance()->url();
        $result = [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'request_url' => $url
        ];

        return json($result, $this->httpCode);
    }

    private function log(Exception $e)
    {
        Log::init([
            'type'  =>  'File',
            'path'  =>  LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}