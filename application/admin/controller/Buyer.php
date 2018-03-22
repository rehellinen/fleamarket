<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:18
 */

namespace app\admin\controller;

use app\common\model\Buyer as BuyerModel;

class Buyer extends BaseController
{
    public function index()
    {
        $buyer = (new BuyerModel)->getNotDelete();
        return $this->fetch('',[
            'buyer' => $buyer
        ]);
    }

    public function delete()
    {
        $buyer = model('Buyer')->getBuyer(-1);
        return $this->fetch('',[
            'buyer' => $buyer
        ]);
    }
}