<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\service\BuyerToken;
use app\common\service\SellerToken;
use app\common\validate\Token as TokenValidate;
use think\Request;

class Token extends BaseController
{
    public function getBuyerToken($code = '')
    {
        (new TokenValidate())->goCheck('buyerToken');

        $userTokenService = new BuyerToken($code);
        $token = $userTokenService->get();

        return $token;
    }

    public function getSellerToken($tele, $password)
    {
        (new TokenValidate())->goCheck('sellerToken');

        $sellerTokenService = new SellerToken($tele, $password);
        $token = $sellerTokenService->get();
    }
}