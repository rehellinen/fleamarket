<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:49
 */

namespace app\common\model;


class Goods extends Base
{
    public function getSoldGoods()
    {
        $data['status'] = 2;
        return $this->where($data)->select();
    }

    public function getNotSold()
    {
        $data['status'] = 1;
        return $this->where($data)->order('listorder desc, id desc')->paginate(12);
    }

    public function updateById($id, $data)
    {
        return $this->where('id='.$id)->update($data);
    }

    public function getSellerGoods($sellerId)
    {
        $condition['status'] = 1;
        $condition['seller_id'] = $sellerId;
        return $this->where($condition)->order('listorder desc, id desc')->paginate(13);
    }

    public function getGoods($status)
    {
        $data['status'] = $status;
        return $this->where($data)->order('listorder desc, id desc')->paginate(10);
    }

    public function getGoodsCount()
    {
        $data['status'] = 1;
        return $this->where($data)->count();
    }
}