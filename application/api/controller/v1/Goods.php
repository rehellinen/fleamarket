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

class Goods extends BaseController
{
    /**
     * 获取所有二手 / 自营商品
     * @param int $type 商品类型
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws GoodsException 找不到商品
     * @throws SuccessMessage
     */
    public function getGoods($type, $page = 1, $size = 14)
    {
        (new Common())->goCheck('page');
        (new Common())->goCheck('type');
        $goods = (new GoodsModel)->getGoods($type, StatusEnum::Normal, $page, $size);
        if($goods->isEmpty()){
            throw new GoodsException([
                'data' => ['data' => []]
            ]);
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取所有产品信息成功'
        ]);
    }

    /**
     * 获取首页推荐商品
     * @param int $type 商品类型
     * @throws SuccessMessage
     */
    public function getIndexGoods($type)
    {
        (new Common())->goCheck('type');
        $goods = (new GoodsModel())->getIndexGoods($type);

        throw new SuccessMessage([
            'message' => '获取首页商品信息成功',
            'data' => $goods
        ]);
    }

    /**
     * 根据商品ID获取商品详情
     * @param int $id 商品ID
     * @param int $type 商品类型（不需要传入）
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getGoodsById($id, $type)
    {
        (new Common())->goCheck('id');
        (new Common())->goCheck('type');
        $goods = (new GoodsModel())->GetGoodsByID($type, StatusEnum::Normal, $id);

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    /**
     * 根据二手 / 自营卖家ID获取商品
     * 此API没有加入权限控制，只能获取Status为1的商品
     * @param int $id 二手卖家 / 自营商家ID
     * @param int $type 商品类型
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws GoodsException 找不到商品
     * @throws SuccessMessage
     */
    public function getGoodsByForeignId($id, $type, $page = 1, $size = 14)
    {
        (new Common())->goCheck('id');
        (new Common())->goCheck('page');
        (new Common())->goCheck('type');
        $goods = (new GoodsModel())->getByForeignID($type, StatusEnum::Normal, $id, $page, $size);

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

    /**
     * 检查商品的信息是否更改
     * @param string $ids 类似于 12|34|56 的格式
     * @throws SuccessMessage
     */
    public function checkInfo($ids)
    {
        (new Common())->goCheck('ids');
        $goodsValidate = (new \app\common\validate\Goods());
        $goodsValidate->goCheck('ids');

        $goods = (new GoodsModel())->getByIDs($ids);

        throw new SuccessMessage([
            'message' => '获取商品信息成功',
            'data' => $goods
        ]);
    }

    /**
     * 根据分类ID获取商品
     * @param int $id 分类ID
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getGoodsByCategoryId($id, $page = 1, $size = 12)
    {
        (new Common())->goCheck('id');
        (new Common())->goCheck('page');

        $goods = (new GoodsModel())->getByCategoryID($id, $page, $size);

        if($goods->isEmpty()){
            throw new GoodsException([
                'data' => ['data' => []]
            ]);
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    /**
     * 根据商品ID获取最近新品
     * @param int $id 分类ID
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getRecentNewGoodsByShopId($id)
    {
        (new Common())->goCheck('id');
        $goods = (new GoodsModel())->getRecentShopNewGoods($id);

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }
}