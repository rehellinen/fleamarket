<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/19
 * Time: 18:30
 */

namespace app\api\controller\v1;

use app\common\exception\GoodsException;
use app\common\exception\SuccessException;
use app\common\model\Goods as GoodsModel;

class Goods extends BaseController
{
    public function getGoods()
    {
        $goods = (new GoodsModel)->getNotSold();
        if(!$goods){
            throw new GoodsException();
        }

        throw new SuccessException([
            'data' => $goods,
            'message' => '获取所有产品信息成功'
        ]);
    }
}