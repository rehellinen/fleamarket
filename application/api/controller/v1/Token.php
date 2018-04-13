<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\common\exception\SuccessMessage;
use app\common\service\BuyerToken;
use app\common\service\SellerToken;
use app\common\validate\Token as TokenValidate;
use app\common\service\Token as TokenService;

class Token extends BaseController
{
    /**
     * 获取买家的Token
     * @param string $code 小程序端生成的code码
     * @throws SuccessMessage 返回Token令牌
     */
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

    /**
     * 获取二手卖家的Token
     * @param string $code 小程序端生成的code码
     * @throws SuccessMessage 返回Token令牌
     */
    public function getSellerToken($code = '')
    {
        (new TokenValidate())->goCheck('get');

        $sellerTokenService = new SellerToken($code);
        $tokenInfo = $sellerTokenService->get();

        throw new SuccessMessage([
            'message' => '获取令牌成功',
            'data' => $tokenInfo
        ]);
    }

    public function verifyOpenID($code = '')
    {
        (new TokenValidate())->goCheck('get');

        $sellerTokenService = new SellerToken($code);
        $wxResult = $sellerTokenService->getResultFromWx();
        $openID = $wxResult['openid'];
        $sellerTokenService->isRegister($openID);

        throw new SuccessMessage([
            'message' => 'openID已存在'
        ]);
    }

    /**
     * 验证Token令牌是否有效
     * @param string $token Token令牌
     * @throws SuccessMessage 返回是否有效
     */
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