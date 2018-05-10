<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/5/10
 * Time: 15:36
 */

namespace app\common\model;


use enum\StatusEnum;

class Admin extends BaseModel
{
    public function getByAccount($account)
    {
        return $this->where([
            'status' => StatusEnum::NORMAL,
            'account' => $account
        ])->find();
    }
}