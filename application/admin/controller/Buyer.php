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
        $buyer = (new BuyerModel)->getRegisterBuyer();
        $page = $buyer->render();

        // 删除空的数据
//        $buyer = $buyer->toArray();
//        $buyer = $buyer['data'];
//        foreach ($buyer as $key => $value){
//            if ($value['name'] == null){
//                unset($buyer[$key]);
//            }
//        }

        return $this->fetch('',[
            'buyer' => $buyer,
            'page' => $page
        ]);
    }
}