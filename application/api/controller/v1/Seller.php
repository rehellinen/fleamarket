<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 23:28
 */

namespace app\api\controller\v1;


class Seller extends BaseController
{
    public function addSeller()
    {
        // 数据校验
        $shopValidate = new \app\common\validate\Seller();
        $shopValidate->goCheck('register');
        $data = $shopValidate->getDataByScene('register');

        $this->insertOrUpdate('Seller', $data);
    }
}