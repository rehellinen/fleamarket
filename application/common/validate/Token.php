<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/23
 * Time: 19:27
 */

namespace app\common\validate;

class Token extends BaseValidate
{
    protected $rule = [
        ['code', 'require', 'code码不能为空']
    ];

    protected $scene = [
        'token' => 'code'
    ];
}