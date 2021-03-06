<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:46
 */

namespace app\common\model;

use enum\StatusEnum;
use enum\TypeEnum;
use app\common\exception\ShopException;

class Shop extends BaseModel
{
    public function mainImageId()
    {
        return $this->hasMany('Goods', 'foreign_id', 'id');
    }

    public function topImageId()
    {
        return $this->belongsTo('Image', 'top_image_id', 'id');
    }

    public function avatarImageId()
    {
        return $this->belongsTo('Image', 'avatar_image_id', 'id');
    }

    public function getNormalShop($page, $size)
    {
        $data['status'] = StatusEnum::NORMAL;
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->with(['mainImageId' => function ($query) {
            $query->where(['type'=>TypeEnum::NewGoods, 'status' => StatusEnum::NORMAL])
                ->order('listorder desc, id desc')->with('imageId');
        }])->with('avatarImageId')->order($order)->paginate($size, true, [
            'page' => $page
        ])
        ->hidden([
        'status', 'listorder', 'number', 'dormitory', 'open_id',
        'avatar_image_id' => ['status'],
        'main_image_id' => ['listorder', 'status']
    ]);
    }

    public function getNormalById($id)
    {
        $condition['id'] = $id;
        $condition['status'] = StatusEnum::NORMAL;
        $shop = $this->with(['topImageId', 'avatarImageId'])->where($condition)->find();
        if(!$shop){
            throw new ShopException();
        }
        return $shop->hidden(['listorder', 'status', 'open_id',
            'top_image_id' => ['status'], 'avatar_image_id' => ['status']]);
    }

    public function getAdminShop($id)
    {
        $shop = $this->with(['mainImageId' => function ($query) {
            $query->with(['imageId'])->order('listorder desc, id desc');
        }])->with([
            'topImageId', 'avatarImageId'
        ])->where('id', '=', $id)->find()->toArray();
        return $shop;
    }
}