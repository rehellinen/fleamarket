<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 11:10
 */

namespace app\common\model;


use enum\StatusEnum;

class Theme extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }
    // 获取首页主题
    public function getIndexTheme()
    {
        $condition = [
            'status' => StatusEnum::Normal
        ];
        return $this->where($condition)->with('imageId')->order('listorder desc, id desc')->limit(4)->select();
    }
}