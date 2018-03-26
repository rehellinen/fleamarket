<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:25
 */

namespace app\admin\controller;

use app\common\model\Goods as GoodsModel;
use app\common\model\Shop;

class Goods extends BaseController
{
    public function index()
    {
        $goods = (new GoodsModel())->getNotDelete();
        return $this->fetch('', [
            'goods' => $goods
        ]);
    }

    public function edit()
    {
        $shop = (new Shop())->getNormal();
        $this->assign('shop', $shop);
        return parent::edit();
    }

    public function add()
    {
        $shop = (new Shop())->getNormal();
        $this->assign('shop', $shop);
        return parent::add();
    }
}