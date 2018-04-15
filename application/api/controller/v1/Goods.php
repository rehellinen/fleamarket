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
     * 获取首页推荐商品
     * @param $type
     * @throws SuccessMessage
     */
    public function getIndexGoods($type)
    {
        $goods = (new GoodsModel())->where([
            'status' => StatusEnum::Normal,
            'type' => $type
        ])->with('imageId')->select()->toArray();
        $resGoods = [];
        $numArr = generateNumber(count($goods), 6);

        foreach ($numArr as $value){
            array_push($resGoods, $goods[$value]);
        }
        throw new SuccessMessage([
            'message' => '获取首页商品信息成功',
            'data' => $resGoods
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
        $goods = (new GoodsModel())->generalGetByID($type, StatusEnum::Normal, $id);
        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessMessage([
            'data' => $goods,
            'message' => '获取产品信息成功'
        ]);
    }

    /**
     * 根据二手 / 自营卖家ID获取商品
     * 此API没有加入权限控制，只能获取Status为1的商品
     * @param $id
     * @param $type
     * @param int $page
     * @param int $size
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getGoodsByForeignId($id, $type, $page = 1, $size = 14)
    {
        (new Common())->goCheck('id');
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
     * @throws SuccessMessage
     */
    public function checkInfo()
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

    /**
     * 获取所有二手 / 自营商品
     * @param int $type 商品类型
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getGoods($type, $page = 1, $size = 14)
    {
        $goods = (new GoodsModel)->generalGet($type, StatusEnum::Normal, $page, $size);
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
     * 根据分类ID获取商品
     * @param $id
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws GoodsException
     * @throws SuccessMessage
     */
    public function getGoodsByCategoryId($id, $page = 1, $size = 12)
    {
        (new Common())->goCheck('id');
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
}