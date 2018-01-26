<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use app\common\service\UserToken;
use think\Controller;
use app\common\validate\Token as tokenValidate;

class Token extends Controller
{
    public function getToken($code = '')
    {
        (new tokenValidate())->goCheck('token');

        $userTokenService = new UserToken($code);
        $token = $userTokenService->get();

        return $token;
    }
}