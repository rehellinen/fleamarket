<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:49
 */

namespace app\common\model;


use enum\StatusEnum;
use enum\TypeEnum;

class Goods extends BaseModel
{
    public function getImageIdAttr($value)
    {
        $value = Image::get($value);
        return $value['image_url'];
    }

    public function getShopIdAttr($value)
    {
        $value = Shop::get($value);
        return $value['name'];
    }

    // 获取商品 / 旧物
    public function generalGet($type, $status)
    {
        $data = [
            'type' => $type,
            'status' => ['in', $status]
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }

    // 根据id获取商品 / 旧物
    public function generalGetByID($type, $status, $id)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'id' => $id
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }

    // 根据商店id获取商品 / 旧物
    public function generalGetByForeignID($type, $status, $foreignId)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'foreign_id' => $foreignId
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }

    // 根据商店id获取最近新品
    public function getRecentShopNewGoods($shopId)
    {
        $data = [
            'status' => StatusEnum::Normal,
            'type' => TypeEnum::NewGoods,
            'foreign_id' => $shopId
        ];
        return $this->where($data)->order('listorder desc, id desc')->limit(config('admin.max_recent_count'))->select();
    }
}