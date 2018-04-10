<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\common\exception\ParameterException;
use app\common\exception\SuccessMessage;
use app\common\service\BuyerToken;
use app\common\validate\Token as TokenValidate;
use app\common\service\Token as TokenService;

class Token extends BaseController
{
    public function getBuyerToken($code = '')
    {
        (new TokenValidate())->goCheck('get');

        $userTokenService = new BuyerToken($code);
        $token = $userTokenService->get();

        throw new SuccessMessage([
            'message' => '获取令牌成功',
            'data' => array('token' => $token)
        ]);
    }

    public function getSellerToken()
    {
        (new TokenValidate())->goCheck('get');
    }

    public function verifyToken($token = '')
    {
        (new TokenValidate())->goCheck('verify');

        $valid = TokenService::verifyToken($token);
        throw new SuccessMessage([
            'message' => '获取Token状态成功',
            'data' => ['isValid' => $valid]
        ]);
    }
}