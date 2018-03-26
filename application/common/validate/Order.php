<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 20:45
 */

namespace app\common\validate;


use app\common\exception\ParameterException;

class Order extends BaseValidate
{
    // products 为二维数组
    protected $rule = [
        ['goods',  'checkGoods']
    ];

    protected $singleRule = [
        ['goods_id',  'require'],
        ['count',  'require'],
    ];

    protected $scene = [
        'order' => ['goods']
    ];

    protected function checkGoods($value)
    {
        if(!is_array($value)){
            throw new ParameterException([
                'message' => '商品参数错误'
            ]);
        }
        if(empty($value)){
            throw new ParameterException([
                'message' => '商品列表不能为空'
            ]);
        }
        foreach ($value as $v){
            $this->checkGoodsProperties($v);
        }
    }

    protected function checkGoodsProperties($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $res = $validate->check($value);
        if(!$res){
            throw new ParameterException([
                'message' => '商品参数错误'
            ]);
        }
    }
}