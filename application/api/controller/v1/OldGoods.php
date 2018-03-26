<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 15:22
 */

namespace app\api\controller\v1;

use app\common\model\OldGoods as OldGoodsModel;
use app\common\exception\OldGoodsException;
use app\common\exception\SuccessMessage;
use app\common\validate\Common;

class OldGoods extends BaseController
{
    public function getOldGoods()
    {
        $goods = (new OldGoodsModel)->getNormal();
        if(!$goods){
            throw new OldGoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取所有二手商品信息成功'
        ]);
    }

    public function getOldGoodsById($id)
    {
        (new Common())->goCheck('id');
        $goods = (new OldGoodsModel())->getNormalById($id);
        if(!$goods){
            throw new OldGoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取二手商品信息成功'
        ]);
    }

    public function getOldGoodsBySellerId($id)
    {
        (new Common())->goCheck('id');
        $goods = (new OldGoodsModel())->getSellerGoods($id);

        if(!$goods){
            throw new OldGoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取二手商品信息成功'
        ]);
    }
}