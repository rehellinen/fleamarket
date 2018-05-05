<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/1
 * Time: 16:34
 */

namespace app\common\model;


use enum\StatusEnum;

class ThemeCategory extends BaseModel
{
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function themeId()
    {
        return $this->belongsTo('Theme', 'theme_id', 'id');
    }

    public function getCategoryByThemeID($themeID)
    {
        $data = [
            'status' => 1,
            'theme_id' => $themeID
        ];
        return $this->where($data)->with('imageId')->limit(15)->order('listorder desc, id desc')
            ->select()->hidden(['status', 'listorder', 'image_id' => ['status']]);
    }

    public function getCategory()
    {
        $data = [
            'status' => ['neq', StatusEnum::DELETED],
        ];
        $category = $this->where($data)->with(['themeId'])->order('listorder desc, id desc')->select()->toArray();
        foreach ($category as $key => $value){
            $category[$key]['theme_id'] = $value['theme_id']['name'];
        }
        return $category;
    }
}