<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/8
 * Time: 13:02
 */

namespace app\admin\controller;


use app\common\model\ThemeCategory as ThemeCategoryModel;
use app\common\model\Theme;

class ThemeCategory extends BaseController
{
    public function index()
    {
        $category = (new ThemeCategoryModel())->getCategory();
        return $this->fetch('', [
            'category' => $category
        ]);
    }

    public function add()
    {
        $theme = (new Theme())->getNormal();
        $this->assign('theme', $theme);
        return parent::add();
    }

    public function edit()
    {
        $theme = (new Theme())->getNormal();
        $this->assign('theme', $theme);
        return parent::edit();
    }
}