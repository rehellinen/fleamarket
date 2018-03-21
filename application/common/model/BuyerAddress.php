<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/21
 * Time: 13:33
 */

namespace app\common\model;


class BuyerAddress extends BaseModel
{
    public function getAddressByBuyerID($buyerID)
    {
        return $this->where('buyer_id='.$buyerID)->find();
    }
}