<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/19
 * Time: 16:12
 */

namespace app\api\controller\v1;

use app\common\exception\ForbiddenException;
use enum\ScopeEnum;
use think\Controller;
use app\common\service\Token as TokenService;

class BaseController extends Controller
{
    // 验证Token令牌是否为买家权限
    protected function checkBuyerScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::Buyer){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }
}