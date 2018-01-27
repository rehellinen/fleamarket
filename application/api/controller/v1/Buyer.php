<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 17:05
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\exception\BuyerException;
use app\common\service\Token as TokenService;
use app\common\validate\Buyer as BuyerValidate;

class Buyer extends BaseController
{
    public function createOrUpdateAddress()
    {
        (new BuyerValidate())->goCheck('address');
        $buyerID = (new TokenService())->getIDByToken();

        $buyer = model('buyer')->get($buyerID);
        if(!$buyerID){
            throw new BuyerException();
        }
    }
}