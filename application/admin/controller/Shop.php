<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:45
 */

namespace app\admin\controller;

use app\common\model\Shop as ShopModel;

class Shop extends BaseController
{
    public function index()
    {
        $shop = (new ShopModel())->getNotDelete();
        return $this->fetch('', [
            'shop' => $shop
        ]);
    }
}