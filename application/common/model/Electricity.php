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
        $seconds = 86400;
        $today = ((round(time() / $seconds, 0) - 1) * $seconds) + $seconds / 6;
        $dateArr = [
            $today,
            $today - $seconds,
            $today - $seconds * 2
        ];
        
        $condition = [
            'buyer_id' =>$buyerID,
            'status' => StatusEnum::NORMAL,
            'check_date' => ['in', $dateArr]
        ];
        $elecArr = $this->where($condition)->order('check_date desc, id asc')
                        ->select()->toArray();

        $threeDaysElecArr = [];
        foreach ($elecArr as $key => $value){
            if($value['check_date'] == $today){
                $threeDaysElecArr[0] = $value['cost_elec'];
            }elseif($value['check_date'] == ($today - $seconds)){
                $threeDaysElecArr[1] = $value['cost_elec'];
            }else{
                $threeDaysElecArr[2] = $value['cost_elec'];
            }
        }
        $sum = array_sum($threeDaysElecArr);
        return $sum;
    }

    public function getSurplus($buyerID)
    {
        $condition = [
            'buyer_id' =>$buyerID,
            'status' => StatusEnum::NORMAL
        ];

        $elec = $this->where($condition)->order('check_date desc')->find();
        $surplus = $elec->remain;
        return $surplus;
    }
}