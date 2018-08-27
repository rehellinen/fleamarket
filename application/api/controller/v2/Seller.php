<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 23:28
 */

namespace app\api\controller\v2;

use app\common\exception\SellerException;
use app\common\validate\Common;
use app\common\model\Seller as SellerModel;
use app\common\exception\SuccessMessage;

class Seller extends BaseController
{
    protected $beforeActionList = [

    ];

    /**
     * 添加 / 修改二手卖家
     */
    public function addOrEditSeller()
    {
        // 数据校验
        $shopValidate = new \app\common\validate\Seller();
        $shopValidate->goCheck('register');
        $data = $shopValidate->getDataByScene('register');

        $this->insertOrUpdate('Seller', $data);
    }

    /**
     * 获取二手卖家
     * @param int $id 二手卖家ID
     * @throws SellerException 卖家不存在
     * @throws SuccessMessage
     */
    public function getSellerByID($id)
    {
        (new Common())->goCheck('id');
        $seller = (new SellerModel())->getNormalById($id);

        throw new SuccessMessage([
            'message' => '获取二手卖家信息成功',
            'data' => $seller
        ]);
    }
}