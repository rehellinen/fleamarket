<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2017/9/28
 * Time: 20:23
 */

namespace app\common\validate;

use think\Validate;

class Goods extends BaseValidate
{
    protected $rule = [
        ['name', 'require', '商品名字不能为空'],
        ['price', 'require|number', '价格不能为空|价格必须为数字'],
        ['quantity', 'require|number', '数量不能为空|数量必须为数字'],
        ['description', 'require', '商品描述不能为空'],
        ['image_id', 'require', '图片不能为空'],
        ['price', 'require'],
        ['ids', 'require|checkIDs'],
        ['listorder', 'require', '排序不能为空'],
        ['id', 'require', '商品ID不能为空']
    ];

    protected $scene = [
        'add' => ['name', 'price', 'quantity', 'description', 'image_id', 'subtitle', 'listorder'],
        'edit' => ['name', 'price', 'quantity', 'description', 'image_id', 'subtitle', 'listorder', 'id'],
        'ids' => ['ids']
    ];

    protected function checkIDs($value)
    {
        $valueArray = explode('|', $value);
        foreach ($valueArray as $v){
            if(!is_numeric($v)){
                return false;
            }
        }
        return true;
    }
}