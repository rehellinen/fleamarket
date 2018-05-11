<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 17:25
 */

namespace app\common\model;


use app\common\exception\BuyerException;
use enum\StatusEnum;

class Buyer extends BaseModel
{
    /**
     * 获取所有已经注册的买家
     */
    public function getRegisterBuyer()
    {
        $buyers = $this->where([
            'status' => ['neq', StatusEnum::DELETED],
            'name' => ['neq', ''],
            'telephone' => ['neq', '']
        ])->paginate();

        return $buyers;
    }

    // 根据id判断信息是否审核通过 / 未删除
    public function isExistedByID($id)
    {
        $data['status'] = StatusEnum::NORMAL;
        $data['id'] = $id;
        $buyer = $this->where($data)->find();
        if(!$buyer){
            throw new BuyerException();
        }
        return $buyer->hidden(['listorder', 'open_id', 'status']);
    }
}