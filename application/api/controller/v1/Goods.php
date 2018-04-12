<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/19
 * Time: 18:30
 */

namespace app\api\controller\v1;

use app\common\exception\GoodsException;
use app\common\exception\SuccessMessage;
use app\common\model\Goods as GoodsModel;
use app\common\validate\Common;
use enum\StatusEnum;
use enum\TypeEnum;

class Goods extends BaseController
{
    public function getNewGoods()
    {
        $goods = (new GoodsModel)->generalGet(TypeEnum::NewGoods, StatusEnum::Normal);
        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取所有产品信息成功'
        ]);
    }

    public function getNewGoodsById($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->generalGetByID(TypeEnum::NewGoods, StatusEnum::Normal, $id);
        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getNewGoodsByShopId($id, $page = 1, $size = 14)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->generalGetByForeignID(TypeEnum::NewGoods, StatusEnum::Normal, $id, $page, $size);

        if($goods->isEmpty()){
            throw new GoodsException([
                'data' => [
                    'data' => []
                ]
            ]);
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getRecentNewGoodsByShopId($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->getRecentShopNewGoods($id);

        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getOldGoods()
    {
        $goods = (new GoodsModel())->generalGet(TypeEnum::OldGoods, StatusEnum::Normal);

        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getOldGoodsById($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->generalGetByID(TypeEnum::OldGoods, StatusEnum::Normal, $id);
        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getOldGoodsBySellerId($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->generalGetByForeignID(TypeEnum::OldGoods, StatusEnum::Normal, $id);

        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function getOldGoodsByCategoryId($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->generalGetByCategoryID(TypeEnum::OldGoods, StatusEnum::Normal, $id);

        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    public function checkPrice()
    {
        $goodsValidate = (new \app\common\validate\Goods());
        $goodsValidate->goCheck('ids');
        $ids = $goodsValidate->getDataByScene('ids');
        $idsArray = explode('|', $ids['ids']);
        $goods = (new \app\common\model\Goods())->where([
            'id' => ['in', $idsArray],
            'status' => StatusEnum::Normal
        ])->with('imageId')->select()->hidden([
            'status', 'quantity', 'description', 'foreign_id', 'listorder', 'subtitle', 'category_id'
        ]);
        throw new SuccessMessage([
            'message' => '获取商品信息成功',
            'data' => $goods
        ]);
    }
}