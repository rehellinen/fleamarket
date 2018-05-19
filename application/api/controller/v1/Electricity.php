<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/5/19
 * Time: 15:28
 */

namespace app\api\controller\v1;

use app\common\exception\SuccessMessage;
use app\common\service\Token as TokenService;
use app\common\model\Electricity as ElectricityModel;

class Electricity extends BaseController
{
    protected $beforeActionList = [
        'checkBuyerScope' => ['only', 'getSurplus,getThreeDays']
    ];

    public function getSurplus()
    {
        $buyerID = TokenService::getBuyerID();
        $elec = (new ElectricityModel())->getSurplus($buyerID);

        throw new SuccessMessage([
            'message' => '获取剩余电费成功',
            'data' => [
                'elec' => $elec
            ]
        ]);
    }

    public function getThreeDays()
    {
        $buyerID = TokenService::getBuyerID();
        $elec = (new ElectricityModel())->getRecentThreeDays($buyerID);

        throw new SuccessMessage([
            'message' => '获取近三天电费成功',
            'data' => [
                'elec' => $elec
            ]
        ]);
    }
}