<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:46
 */

namespace app\common\model;

use enum\StatusEnum;

class Shop extends BaseModel
{
    public function mainImageId()
    {
        return $this->hasMany('ShopMainImage', 'shop_id', 'id');
    }

    public function topImageId()
    {
        return $this->belongsTo('Image', 'top_image_id', 'id');
    }

    public function avatarImageId()
    {
        return $this->belongsTo('Image', 'avatar_image_id', 'id');
    }

    public function getNormal()
    {
        $data['status'] = StatusEnum::Normal;
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->with(['mainImageId', 'avatarImageId'])->order($order)->paginate();
    }

    public function getNormalById($id)
    {
        $condition['id'] = $id;
        $condition['status'] = StatusEnum::Normal;
        return $this->with(['topImageId', 'avatarImageId'])->where($condition)->find();
    }

    public function getAdminShop($id)
    {
        $shop = $this->with(['mainImageId' => function($query){
            $query->with(['imageId'])->order('listorder desc, id desc');
        }])->with([
            'topImageId', 'avatarImageId'
        ])->where('id', '=', $id)->find()->toArray();
        return $shop;
    }
}