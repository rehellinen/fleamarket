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
use enum\StatusEnum;
use enum\TypeEnum;

class Goods extends BaseController
{
    public function newGoods()
    {
        $newGoods = (new GoodsModel())->generalGet(TypeEnum::NewGoods, [
            StatusEnum::Normal, StatusEnum::NotPass
        ]);
        return $this->fetch('', [
            'goods' => $newGoods
        ]);
    }

    public function oldGoods()
    {
        $oldGoods = (new GoodsModel())->generalGet(TypeEnum::OldGoods, [
            StatusEnum::Normal, StatusEnum::NotPass
        ]);
        return $this->fetch('', [
            'goods' => $oldGoods
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