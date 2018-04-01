<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/1
 * Time: 16:34
 */

namespace app\common\model;


class ThemeCategory extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function getCategoryByThemeID($themeID)
    {
        $data = [
            'status' => 1,
            'theme_id' => $themeID
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')->select();
    }
}