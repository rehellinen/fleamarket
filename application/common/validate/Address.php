<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/1/27
 * Time: 12:10
 */

namespace app\common\validate;


class Address extends BaseValidate
{
    protected $rule = [
        ['name', 'require|isNotEmpty'],
        ['telephone', 'require|isNotEmpty'],
        ['detail', 'require|isNotEmpty'],
        ['number', 'require|isNotEmpty'],
        ['wechat', 'require']
    ];

    protected $scene = [
        ['new' => 'name', 'telephone', 'detail', 'number', 'wechat']
    ];
}