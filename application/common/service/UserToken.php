<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:07
 */

namespace app\common\service;


use app\common\exception\WeChatException;
use think\Exception;

class UserToken
{
    protected $code;
    protected $appId;
    protected $appSecret;
    protected $loginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('weixin.app_id');
        $this->appSecret = config('weixin.app_secret');
        $this->loginUrl = sprintf(config('weixin.url'), $this->appId, $this->appSecret, $this->code);
    }

    public function get()
    {
        $result = curl_get($this->loginUrl);
        $wxResult = json_decode($result, true);

        if(empty($wxResult)) {
            throw new Exception('获取open_id失败，微信服务器错误');
        }else{
            $loginFail = array_key_exists('errcode', $wxResult);
            if($loginFail){

            }else{

            }
        }
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'message' => $wxResult['errmsg'],
            'status' => $wxResult['errcode']
        ]);
    }
}