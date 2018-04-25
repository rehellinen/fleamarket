<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 19:26
 */

namespace app\api\controller\v1;

use app\common\exception\SuccessMessage;
use app\common\exception\ThemeException;
use app\common\model\Theme as ThemeModel;
use app\common\model\ThemeCategory;
use app\common\validate\Common;

class Theme extends BaseController
{
    /**
     * 获取首页的四个主题
     * @throws SuccessMessage
     * @throws ThemeException 主题不存在
     */
    public function getIndexNormalTheme()
    {
        $theme = (new ThemeModel())->getIndexTheme();
        if(!$theme){
            throw new ThemeException();
        }
        throw new SuccessMessage([
            'message' => '获取主题数据成功',
            'data' => $theme
        ]);
    }

    /**
     * 获取主题对应的分类
     * @param $id
     * @throws SuccessMessage
     * @throws ThemeException
     */
    public function getThemeCategory($id)
    {
        (new Common())->goCheck('id');

        $category = (new ThemeCategory())->getCategoryByThemeID($id);
        if(!$category){
            throw new ThemeException([
                'message' => '获取该主题分类失败',
                'status' => 70001
            ]);
        }
        throw new SuccessMessage([
            'message' => '获取主题分类数据成功',
            'data' => $category
        ]);
    }
}