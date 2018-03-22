<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:25
 */

namespace app\admin\controller;

use app\common\model\Goods as GoodsModel;


class Goods extends BaseController
{
    public function index()
    {
        $goods = (new GoodsModel())->getNormal();
        return $this->fetch('', [
            'goods' => $goods
        ]);
    }
}