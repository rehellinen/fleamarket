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
        $get = $this->request->get();
        if($get){
            $parentID = $get['id'];
            $hidden = true;
            $menu = (new MenuModel())->getChildMenuByID($parentID);
        }else{
            $menu = (new MenuModel())->getParentMenu();
            $hidden = false;
        }
        return $this->fetch('', [
            'menu' => $menu,
            'hidden' => $hidden
        ]);
    }

    public function add()
    {
        $menu = (new MenuModel())->getParentMenu();
        $this->assign('menu', $menu);
        return parent::add();
    }

    public function edit()
    {
        $menu = (new MenuModel())->getParentMenu();
        $this->assign('menu', $menu);
        return parent::edit();
    }
}