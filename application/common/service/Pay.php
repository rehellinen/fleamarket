<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/31
 * Time: 15:03
 */

namespace app\common\service;


use think\Exception;

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {

    }
}