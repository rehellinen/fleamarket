<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 17:05
 */

namespace app\api\controller\v1;


use app\common\exception\BuyerException;
use app\common\exception\SuccessMessage;
use app\common\validate\Buyer as BuyerValidate;
use app\common\service\Token as TokenService;
use app\common\model\Buyer as BuyerModel;
use think\Exception;


class Buyer extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerScope' => ['only' => 'updateBuyerInfo, getBuyerInfo']
    ];

    // 更新用户信息
    public function updateBuyerInfo()
    {
        // 根据Token令牌获取用户ID
        $buyerID = TokenService::getBuyerID();
        (new BuyerValidate)->goCheck('update');
        $data = (new BuyerValidate)->getDataByScene('update');

        // 判断用户是否存在
        $buyer = (new BuyerModel)->isExistedByID($buyerID);
        if(!$buyer){
            throw new BuyerException();
        }

        // 进行更新数据操作
        $res = BuyerModel::update($data, ['id' => $buyerID]);
        if(!$res){
            throw new Exception();
        }else{
            throw new SuccessMessage([
                'message' => '更新个人信息成功',
                'httpCode' => 201
            ]);
        }
    }

    public function getBuyerInfo()
    {
        // 根据Token令牌获取用户ID
        $buyerID = TokenService::getBuyerID();
        // 判断用户是否存在
        $buyer = (new BuyerModel)->isExistedByID($buyerID);
        if(!$buyer){
            throw new BuyerException();
        }
        throw new SuccessMessage([
            'message' => '获取个人信息成功',
            'data' => $buyer
        ]);
    }
}