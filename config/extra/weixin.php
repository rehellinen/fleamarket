<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/24
 * Time: 11:17
 */

return [
    // 跳蚤市场客户端
    'app_id' => 'wx1b2ecb8981e28b61',
    'app_secret' => '5af2bd361cf8a199f15dbcda0f76ace1',
    // 跳蚤市场商家版
    'bis_app_id' => 'wx026b7698d91e0904',
    'bis_app_secret' => '903d07a8dd70185c99448784c2bd83a0',
    'url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'pay_back_url' => 'https://20298479.rehellinen.cn/fleamarket/public/api/v1/notify',
    //二维码相关
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    'qrCode_url' => 'https://api.weixin.qq.com/wxa/getwxacode?access_token=%s'
];