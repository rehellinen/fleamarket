<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/5/19
 * Time: 15:36
 */

namespace app\common\model;


use enum\StatusEnum;

class Electricity extends BaseModel
{
    public function getRecentThreeDays($buyerID)
    {
        $sum = 0;
        $condition = [
            'buyer_id' =>$buyerID,
            'status' => StatusEnum::NORMAL
        ];
        $elecArr = $this->where($condition)->limit(3)->order('check_date desc')
                        ->select()->toArray();


        foreach ($elecArr as $key => $value){
            $sum += $value['cost_elec'];
        }

        return $sum;
    }
}