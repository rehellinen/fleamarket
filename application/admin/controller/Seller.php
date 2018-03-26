<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 11:04
 */

namespace app\admin\controller;

use app\common\model\Seller as SellerModel;

class Seller extends BaseController
{
    public function index()
    {
        $seller = (new SellerModel())->getNotDelete();
        return $this->fetch('',[
            'seller' => $seller
        ]);
    }
}