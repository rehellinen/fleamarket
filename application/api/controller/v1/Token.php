<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\common\service\UserToken;
use app\common\validate\Token as TokenValidate;

class Token extends BaseController
{
    public function getToken($code = '')
    {
        (new TokenValidate())->goCheck('token');

        $userTokenService = new UserToken($code);
        $token = $userTokenService->get();

        return $token;
    }
}