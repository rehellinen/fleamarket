<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:49
 */

namespace app\common\model;


use enum\StatusEnum;

class Goods extends BaseModel
{
    // status值
    // -1->被删除, 0->审核不通过, 1->正常, 2->卖出
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

    // 根据商店id获取商品
    public function getShopGoods($shopId)
    {
        $condition['status'] = StatusEnum::Normal;
        $condition['shop_id'] = $shopId;
        return $this->where($condition)->order('listorder desc, id desc')->paginate(13);
    }

    // 获取最近新品
    public function getRecentShopGoods($shopId)
    {
        $condition['status'] = StatusEnum::Normal;
        $condition['shop_id'] = $shopId;
        return $this->where($condition)->order('listorder desc, id desc')->limit(config('admin.max_recent_count'))->select();
    }
}