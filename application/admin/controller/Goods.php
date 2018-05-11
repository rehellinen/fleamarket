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
        $newGoods = (new GoodsModel())->where([
            'type' => TypeEnum::NewGoods,
            'status' => ['in', [StatusEnum::NOTPASS, StatusEnum::NORMAL]]
        ])->order('id desc')->paginate();
        return $this->fetch('', [
            'goods' => $newGoods
        ]);
    }

    public function oldGoods()
    {
        $oldGoods = (new GoodsModel())->where([
            'type' => TypeEnum::OldGoods,
            'status' => ['in', [StatusEnum::NOTPASS, StatusEnum::NORMAL]]
        ])->order('id desc')->paginate();
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