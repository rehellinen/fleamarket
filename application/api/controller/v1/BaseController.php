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
use enum\StatusEnum;
use think\Controller;
use app\common\service\Token as TokenService;
use app\common\service\SellerToken;
use app\common\exception\SuccessMessage;

class BaseController extends Controller
{
    /**
     * 增加卖家 / 更新卖家信息
     * @param static $model 模型名称 : shop / seller
     * @param array $data 用户数据
     * @throws SuccessMessage
     */
    public function insertOrUpdate($model, $data)
    {
        // 获取openID
        $wxRes = (new SellerToken($data['code']))->getResultFromWx();
        $data['open_id'] = $wxRes['openid'];

        $shopModel = model($model);
        $shop = $shopModel->where([
            'open_id' => ['=', $wxRes['openid']],
            'status' => ['neq', StatusEnum::Deleted]
        ])->find();
        unset($data['code']);
        if(!$shop){
            $res = $shopModel->insert($data);
            if($res){
                throw new SuccessMessage([
                    'message' => '注册成功！'
                ]);
            }
        }else{
            $res = $shopModel::update($data, ['open_id' => $wxRes['openid']]);

            if($res){
                throw new SuccessMessage([
                    'message' => '修改信息成功'
                ]);
            }
        }
    }


    /**
     * 验证Token令牌是否为买家权限
     * @return bool
     * @throws ForbiddenException
     */
    protected function checkBuyerScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::Buyer){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }

    /**
     * 验证Token令牌是否为买家、二手商家、自营商家的权限
     * @return bool
     * @throws ForbiddenException
     */
    protected function checkBuyerSellerShopScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::Buyer || $scope == ScopeEnum::Shop || $scope == ScopeEnum::Seller){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }

    /**
     * 验证Token令牌是否为二手商家、自营商家的权限
     * @return bool
     * @throws ForbiddenException
     */
    protected function checkSellerShopScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::Shop || $scope == ScopeEnum::Seller){
            return true;
        }else{
            throw new ForbiddenException();
        }
    }
}