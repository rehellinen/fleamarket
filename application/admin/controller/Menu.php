<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/22
 * Time: 10:33
 */

namespace app\admin\controller;

use app\common\model\Menu as MenuModel;

class Menu extends BaseController
{
    public function index()
    {
        $menu = (new MenuModel())->getNotDelete();
        return $this->fetch('', [
            'menu' => $menu
        ]);
    }
}