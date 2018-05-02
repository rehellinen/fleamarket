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
use app\common\service\Token;
use enum\TypeEnum;
use \app\common\validate\Goods as GoodsValidate;

class Goods extends BaseController
{
    /**
     * @var array 需要权限控制的方法
     */
    protected $beforeActionList = [
        'checkSellerShopScope' => ['only' => 'getDownedGoods,addGoods,updateGoodsStatus,editGoods']
    ];

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
    public function checkInfo($ids = '')
    {
        (new Common())->goCheck('ids');
        $goodsValidate = (new GoodsValidate());
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
    public function getGoodsByCategoryId($id = null, $page = 1, $size = 12)
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
     * 根据商店ID获取最近新品
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

    /**
     * 获取下架的商品
     * @param int $page 页码
     * @param int $size 每页数量
     * @throws  SuccessMessage
     * @throws  GoodsException 商品不存在
     */
    public function getDownedGoods($page, $size)
    {
        (new Common())->goCheck('page');

        $sellerID = Token::getCurrentTokenVar('sellerID');
        $shopID = Token::getCurrentTokenVar('shopID');
        if($sellerID){
            $goods = (new GoodsModel())->getDownedGoods(TypeEnum::OldGoods, $sellerID, $size, $page);
        }else{
            $goods = (new GoodsModel())->getDownedGoods(TypeEnum::NewGoods, $shopID, $size, $page);
        }
        if($goods->isEmpty()){
            throw new GoodsException();
        }
        throw new SuccessMessage([
            'message' => '获取商品信息成功',
            'data' => $goods
        ]);
    }

    /**
     * 添加商品
     */
    public function addGoods()
    {
        (new GoodsValidate())->goCheck('add');
        $data = (new GoodsValidate())->getDataByScene('add');

        $sellerID = Token::getCurrentTokenVar('sellerID');
        $shopID = Token::getCurrentTokenVar('shopID');

        if($sellerID){
            $data['type'] = 2;
            $data['foreign_id'] = $sellerID;
            (new GoodsModel())->save($data);
        }else{
            $data['type'] = 1;
            $data['foreign_id'] = $shopID;
            (new GoodsModel())->save($data);
        }

        throw new SuccessMessage([
            'message' => '添加商品成功'
        ]);
    }

    /**
     * 改变商品的状态
     * @param int $id 商品ID
     * @param int $status 商品状态值
     * @throws SuccessMessage
     */
    public function updateGoodsStatus($id = null, $status = null)
    {
        (new Common())->goCheck('id');
        (new Common())->goCheck('status');
        $sellerID = Token::getCurrentTokenVar('sellerID');
        $shopID = Token::getCurrentTokenVar('shopID');
        if($sellerID){
            $goods = (new GoodsModel())->where([
                'id' => $id,
                'type' => TypeEnum::OldGoods
            ])->find();
        }else{
            $goods = (new GoodsModel())->where([
                'id' => $id,
                'type' => TypeEnum::NewGoods
            ])->find();
        }

        $uid = $goods->foreign_id;
        Token::isValidSellerShop($uid);
        $goods->status = $status;
        $goods->save();
        throw new SuccessMessage([
            'message' => '更改商品状态成功'
        ]);
    }

    /**
     * 编辑商品信息
     * @throws SuccessMessage
     */
    public function editGoods()
    {
        (new GoodsValidate())->goCheck('edit');
        $data = (new GoodsValidate())->getDataByScene('edit');
        $goodsID = $data['id'];
        $sellerID = Token::getCurrentTokenVar('sellerID');
        if($sellerID){
            $goods = (new GoodsModel())->where([
                'id' => $goodsID,
                'type' => TypeEnum::OldGoods
            ])->find();
        }else{
            $goods = (new GoodsModel())->where([
                'id' => $goodsID,
                'type' => TypeEnum::NewGoods
            ])->find();
        }

        $uid = $goods->foreign_id;
        Token::isValidSellerShop($uid);
        $goods->save($data);
        throw new SuccessMessage([
            'message' => '更改商品信息成功'
        ]);
    }
}