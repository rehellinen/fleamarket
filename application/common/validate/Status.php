<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 12:25
 */

namespace app\common\validate;


use think\Validate;

class Status extends Validate
{
    protected $rule = [
        ['id', 'require|number', 'id不能为空|id不合法'],
        ['status', 'require|number', '状态不能为空|状态不合法']
    ];
}