<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 23:37
 */

namespace app\common\service;


use app\common\exception\SellerException;
use app\common\model\Seller;

class SellerToken extends Token
{
    private $tele;
    private $password;

    public function __construct($tele, $password)
    {
        $this->tele = $tele;
        $this->password = $password;
    }

    public function get()
    {
        // 验证用户名、密码是否正确
        $sellerModel = new Seller();
        $seller = $sellerModel->getSellerByTele($this->tele);
        if(!$seller){
            throw new SellerException();
        }

        $md5Password = $seller->password;
    }
}