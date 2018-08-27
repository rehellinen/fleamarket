<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 14:08
 */

namespace app\api\controller\v2;

use app\common\exception\ShopException;
use app\common\exception\SuccessMessage;
use app\common\model\Shop as ShopModel;
use app\common\validate\Common;

class Shop extends BaseController
{
    protected $beforeActionList = [

    ];

    /**
     * 获取商店列表
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws ShopException 商店不存在
     * @throws SuccessMessage
     */
    public function getNormalShop($page = 1, $size = 7)
    {
        $shop = (new ShopModel())->getNormalShop($page, $size);
        if($shop->isEmpty()){
            throw new ShopException([
                'data' => [
                    'data' => []
                ]
            ]);
        }
        throw new SuccessMessage([
            'message' => '获取所有自营商家信息成功',
            'data' => $shop
        ]);
    }

    /**
     * 根据商店ID获取商店详细信息
     * @param $id
     * @throws SuccessMessage
     */
    public function getShopByID($id)
    {
        (new Common())->goCheck('id');
        $shop = (new ShopModel())->getNormalById($id);

        throw new SuccessMessage([
            'message' => '获取自营商家信息成功',
            'data' => $shop
        ]);
    }

    /**
     * 添加 / 修改自营商家
     */
    public function addOrEditShop()
    {
        // 数据校验
        $shopValidate = new \app\common\validate\Shop();
        $shopValidate->goCheck('register');
        $data = $shopValidate->getDataByScene('register');

        $this->insertOrUpdate('Shop', $data);
    }
}