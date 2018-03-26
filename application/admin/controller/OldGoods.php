<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:25
 */

namespace app\admin\controller;

use app\common\model\OldGoods as OldGoodsModel;
use app\common\model\Seller;

class OldGoods extends BaseController
{
    public function index()
    {
        $oldGoods = (new OldGoodsModel())->getNotDelete();
        return $this->fetch('', [
            'oldGoods' => $oldGoods
        ]);
    }

    public function edit()
    {
        $seller = (new Seller())->getNormal();
        $this->assign('seller', $seller);
        return parent::edit();
    }

    public function add()
    {
        $seller = (new Seller())->getNormal();
        $this->assign('seller', $seller);
        return parent::add();
    }
}