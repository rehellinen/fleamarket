<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:49
 */

namespace app\common\model;


use app\common\exception\GoodsException;
use app\common\exception\ShopException;
use enum\StatusEnum;
use enum\TypeEnum;
use think\Paginator;

class Goods extends BaseModel
{
    /**
     * 图片关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function imageId()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    /**
     * 自营商家关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('Shop', 'foreign_id', 'id');
    }

    /**
     * 二手卖家关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo('Seller', 'foreign_id', 'id');
    }

    public function themeId()
    {
        return $this->belongsTo('Theme', 'theme_id', 'id');

    }

    /**
     * 分类关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function categoryId()
    {
        return $this->belongsTo('ThemeCategory', 'category_id', 'id');
    }

    /**
     * 根据多个id获取多个商品
     * @param string $ids
     * @throws GoodsException 找不到商品
     * @return mixed
     */
    public function getByIDs($ids)
    {
        $queryString = $this->getNormalShopOrSeller();
        $idsArray = explode('|', $ids);
        $goods = $this->where($queryString)->where([
            'id' => ['in', $idsArray],
            'status' => StatusEnum::NORMAL
        ])->with('imageId')->select();
        if ($goods->isEmpty()) {
            throw new GoodsException();
        }

        return $goods->hidden([
            'status', 'description', 'foreign_id', 'listorder',
            'subtitle', 'category_id', 'image_id' => ['status']
        ]);
    }

    /**
     * 获所有取商品 / 旧物 （关联图片）
     * @param int $type 商品种类
     * @param int|array $status 商品状态
     * @param int $page 页码
     * @param int $size 每页数量
     * @return mixed
     */
    public function getGoods($type, $status, $page = 1, $size = 14)
    {
        $queryString = $this->getNormalShopOrSeller();
        $data = [
            'type' => $type,
            'status' => ['in', $status]
        ];
        return $goods = $this->where($data)->with(['imageId'])->order('listorder desc, id desc')->where($queryString)
            ->paginate($size, true, [
                'page' => $page
            ])->hidden(['listorder', 'status', 'image_id' => ['status']]);
    }

    /**
     * 获取首页商品 / 旧物 （关联图片）
     * @param int $type 商品类型
     * @throws GoodsException 找不到商品
     * @return array
     */
    public function getIndexGoods($type)
    {
        $queryString = $this->getNormalShopOrSeller();
        $goods = $this->where($queryString)->where([
            'status' => StatusEnum::NORMAL,
            'type' => $type
        ])->with('imageId')->select()->toArray();
        if (!$goods) {
            throw new GoodsException();
        }
        $resGoods = [];
        $numArr = generateNumber(count($goods), 6);

        foreach ($numArr as $value) {
            array_push($resGoods, $goods[$value]);
        }

        return $resGoods;
    }

    /**
     * 根据商品ID获取商品
     * @param int $type 商品种类
     * @param int|array $status 商品状态
     * @param int $id 商品ID
     * @throws GoodsException 找不到商品
     * @return mixed
     */
    public function GetGoodsByID($type, $status, $id)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'id' => $id
        ];

        if ($type == TypeEnum::NewGoods) {
            $related = 'shop';
        } else {
            $related = 'seller';
        }

        $goods = $this->where($data)->with([$related, 'imageId'])->with(['categoryId' => function($query){
            $query->with('themeId');
        }])
            ->order('listorder desc, id desc')->find();
        if (!$goods) {
            throw new GoodsException();
        }
        return $goods = $goods->hidden([
            'shop' => ['listorder', 'status', 'number', 'open_id'],
            'seller' => ['listorder', 'status', 'number', 'open_id'],
            'image_id' => ['status'], 'status',
            'category_id' => ['status', 'listorder', 'image_id']
        ]);
    }

    /**
     * 根据商店id获取商品 / 旧物
     * @param int $type 商品种类
     * @param int|array $status 商品状态
     * @param int $foreignId 二手卖家 / 自营商家ID
     * @param int $page 页码
     * @param int $size 每页数量
     * @return \think\Paginator
     */
    public function getByForeignID($type, $status, $foreignId, $page, $size)
    {
        $data = [
            'status' => ['in', $status],
            'type' => $type,
            'foreign_id' => $foreignId
        ];
        return $this->where($data)->with('imageId')->order('listorder desc, id desc')
            ->paginate($size, true, [
                'page' => $page
            ])->hidden(['status', 'listorder', 'image_id' => ['status']]);
    }

    /**
     * 根据商店id获取最近新品
     * @param int $shopId 商店ID
     * @return mixed
     * @throws GoodsException 找不到商品
     */
    public function getRecentShopNewGoods($shopId)
    {
        // 判断商店 / 卖家是否审核通过
        $this->isShopSellerNormal(TypeEnum::NewGoods, $shopId);
        $data = [
            'status' => StatusEnum::NORMAL,
            'type' => TypeEnum::NewGoods,
            'foreign_id' => $shopId
        ];
        $goods = $this->where($data)->with('imageId')->order('id desc')
            ->limit(config('admin.max_recent_count'))->select();

        if (!$goods) {
            throw new GoodsException();
        }

        return $goods->hidden(['status', 'listorder', 'image_id' => ['status']]);
    }

    /**
     * 根据分类ID获取商品
     * @param int $categoryID 分类ID
     * @param int $page 页码
     * @param int $size 每页数量
     * @return mixed
     */
    public function getByCategoryID($categoryID, $page, $size)
    {
        $queryString = $this->getNormalShopOrSeller();
        $data = [
            'status' => StatusEnum::NORMAL,
            'category_id' => $categoryID
        ];
        return $this->where($data)->where($queryString)->with('imageId')
            ->order('listorder desc, id desc')
            ->paginate($size, true, [
                'page' => $page
            ])->hidden(['status', 'listorder', 'image_id' => ['status']]);
    }

    /**
     * 获取下架商品
     * @param int $type
     * @param int $foreignID
     * @param int $size
     * @param int $page
     * @return Paginator
     * @throws GoodsException 商品不存在
     */
    public function getDownedGoods($type, $foreignID, $size, $page)
    {
        $condition = [
            'type' => $type,
            'foreign_id' => $foreignID,
            'status' => StatusEnum::NOTPASS
        ];

        $goods = $this->where($condition)->order('listorder desc, id desc')->with('imageId')
            ->paginate($size, true, ['page' => $page]);

        if($goods->isEmpty()){
            throw new GoodsException();
        }

        return $goods->hidden(['image_id' => ['status']]);
    }

    /**
     * 判断二手卖家 / 店家是否通过审核
     * @param $type
     * @param $foreignID
     * @throws ShopException
     */
    private function isShopSellerNormal($type, $foreignID)
    {
        if($type == TypeEnum::NewGoods){
            $shop = (new Shop())->where('id', '=', $foreignID)->find();
            if(!$shop){
                throw new ShopException();
            }
            if($shop->status != 1){
                throw new ShopException();
            }
        }
        if($type == TypeEnum::OldGoods){
            $seller = (new Seller())->where('id', '=', $foreignID)->find();
            if(!$seller){
                throw new ShopException();
            }
            if($seller->status != 1){
                throw new ShopException();
            }
        }
    }
}