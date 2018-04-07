<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 11:10
 */

namespace app\admin\controller;

use app\common\model\Theme as ThemeModel;

class Theme extends BaseController
{
    public function index()
    {
        $theme = (new ThemeModel())->getHasImage();
        return $this->fetch('', [
            'theme' => $theme
        ]);
    }
}