<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:54
 */

namespace app\admin\controller;


class Deal extends BaseController
{
    public function index()
    {
        $deal = model('Deal')->getDeal();
        return $this->fetch('',[
            'deal' => $deal
        ]);
    }
}