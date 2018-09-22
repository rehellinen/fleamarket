<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/5/2
 * Time: 10:51
 */

namespace app\api\controller\v2;

use app\common\service\WxNotify;

class Weixin extends BaseController
{
    public function getQRCode()
    {
        // 获取access_token
        $access_token_url = sprintf(config('weixin.access_token_url'), config('weixin.app_id'), config('weixin.app_secret'));
        $res = json_decode(curl_http($access_token_url), true);
        $access_token = $res['access_token'];

        // 获取二维码
        $qr_url = sprintf(config('weixin.qrCode_url'), $access_token);
        $jsonData = json_encode([
            'path' => 'pages/index/index'
        ]);
        $qr = curl_http($qr_url, 1, $jsonData);
        (new Image())->saveQRCode($qr);
    }

    public function testTemplate()
    {
        $data = [
            'result_code' => 'SUCCESS',
            'out_trade_no' => 'A921056378737014'
        ];
        $msg = 'test';
        (new WxNotify())->NotifyProcess($data, $msg);
    }
}