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
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function shop()
    {
       return $this->belongsTo('Shop', 'foreign_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('Seller', 'foreign_id', 'id');
    }

    public function categoryId()
    {
        return $this->belongsTo('ThemeCategory', 'category_id', 'id');
    }

    // 获取商品 / 旧物
    public function generalGet($type, $status)
    {
        $data = [
            'type' => $type,
            'status' => ['in', $status]
        ];
        if($type == TypeEnum::NewGoods){
            $related = 'shop';
        }else{
            $related = 'seller';
        }
        return $this->where($data)->with([$related, 'imageId'])->order('listorder desc, id desc')->paginate();
    }

    // 根据id获取商品 / 旧物
    public function generalGetByID($type, $status, $id)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'id' => $id
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')->find();
    }

    // 根据商店id获取商品 / 旧物
    public function generalGetByForeignID($type, $status, $foreignId)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'foreign_id' => $foreignId
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')->paginate();
    }

    // 根据商店id获取最近新品
    public function getRecentShopNewGoods($shopId)
    {
        $data = [
            'status' => StatusEnum::Normal,
            'type' => TypeEnum::NewGoods,
            'foreign_id' => $shopId
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')->limit(config('admin.max_recent_count'))->select();
    }

    // 根据分类id获取商品 / 旧物
    public function generalGetByCategoryID($type, $status, $categoryID)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'category_id' => $categoryID
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')->paginate();
    }
}