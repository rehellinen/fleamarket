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
        ['code', 'require|isNotEmpty', 'code码不能为空|code码不能为空'],
        ['token', 'require|isNotEmpty', 'token不能为空|token不能为空']
    ];

    protected $scene = [
        'get' => 'code',
        'verify' => 'token'
    ];
}