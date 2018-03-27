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
    public function generalGet($type = TypeEnum::NewGoods, $status = [1, 2])
    {
        $data = [
            'type' => $type,
            'status' => ['in', $status]
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }

    // 对自营商品的操作
    // 根据商店id获取商品
    public function getShopGoods($shopId)
    {
        $data = [
            'status' => StatusEnum::Normal,
            'type' => TypeEnum::NewGoods,
            'shop_id' => $shopId
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }

    // 获取最近新品
    public function getRecentShopGoods($shopId)
    {
        $data = [
            'status' => StatusEnum::Normal,
            'type' => TypeEnum::NewGoods,
            'shop_id' => $shopId
        ];
        return $this->where($data)->order('listorder desc, id desc')->limit(config('admin.max_recent_count'))->select();
    }
}