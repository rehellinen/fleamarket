<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/4/8
 * Time: 13:02
 */

namespace app\admin\controller;


use app\common\model\ThemeCategory;

class Category extends BaseController
{
    public function index()
    {
        $category = (new ThemeCategory())->getCategory();
        return $this->fetch('', [
            'category' => $category
        ]);
    }
}