<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 11:04
 */

namespace app\admin\controller;


class Seller extends BaseController
{
    public function index()
    {
        $seller = model('Seller')->getSeller(1);
        return $this->fetch('',[
            'seller' => $seller
        ]);
    }

    public function wait()
    {
        $seller = model('Seller')->getSeller(0);
        return $this->fetch('',[
            'seller' => $seller
        ]);
    }

    public function delete()
    {
        $seller = model('Seller')->getSeller(-1);
        return $this->fetch('',[
            'seller' => $seller
        ]);
    }
}