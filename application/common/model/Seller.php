<?php
namespace app\common\model;
use enum\StatusEnum;

/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 15:36
 */
class Seller extends BaseModel
{
    // 根据电话号获取管理员信息
    public function getRootByTel($tel)
    {
        $data = array(
            'telephone' => $tel,
            'status' => StatusEnum::Normal,
            'is_root' => 1,
        );

        return $this->where($data)->find();
    }

    // 获取卖家的数量
    public function getSellerCount()
    {
        $data['status'] = StatusEnum::Normal;
        return $this->where($data)->count();
    }
}