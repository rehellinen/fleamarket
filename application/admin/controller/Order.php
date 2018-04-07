<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:54
 */

namespace app\admin\controller;


class Order extends BaseController
{
    public function index()
    {
        $order = (new \app\common\model\Order())->getNotDelete();
        return $this->fetch('',[
            'order' => $order
        ]);
    }
}