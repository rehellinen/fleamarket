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

class Theme extends BaseController
{
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
}