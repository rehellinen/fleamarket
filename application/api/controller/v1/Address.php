<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 12:05
 */

namespace app\api\controller\v1;


use app\common\exception\BuyerException;
use app\common\exception\SuccessMessage;
use app\common\model\BuyerAddress;
use think\Controller;
use app\common\validate\Address as AddressValidate;
use app\common\service\Token as TokenService;
use app\common\model\Buyer as BuyerModel;

class Address extends Controller
{
    public function createOrUpdateAddress()
    {
        (new AddressValidate)->goCheck('new');
        $buyerID = TokenService::getBuyerID();
        $data = (new AddressValidate)->getDataByScene('new');
        $data['buyer_id'] = $buyerID;

        // 判断用户是否存在
        $buyer = BuyerModel::get($buyerID);
        if(!$buyer){
            throw new BuyerException();
        }

        // 判断用户地址是否存在
        $address = (new BuyerAddress());
        $buyerAddress = $address->getAddressByBuyerID($buyerID);
        if($buyerAddress){
            $address->save($data, ['buyer_id' => $buyerID]);
        }else{
            $address->save($data);
        }
        throw new SuccessMessage([
            'message' => '更新 / 修改地址成功',
            'httpCode' => 201
        ]);
    }
}