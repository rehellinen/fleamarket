<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/26
 * Time: 17:37
 */

namespace enum;

// 状态值说明
class StatusEnum
{
    // 正常状态
    const NORMAL = 1;

    // 审核不通过
    const NOTPASS = 0;

    // 已删除
    const DELETED = -1;

    // 已卖出（商品独有的状态值）
    const SOLD = 2;
}