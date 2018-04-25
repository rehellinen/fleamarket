<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/31
 * Time: 15:39
 */

namespace enum;


class OrderEnum
{
    // 已删除
    const DELETE = -1;

    // 未付款
    const UNPAID = 1;

    // 已付款，待发货
    const PAID = 2;

    // 已发货
    const DELIVERED = 3;

    // 买家确认收货
    const COMPLETED = 4;

    // 买家已付款但是没有足够的库存
    const PAID_BUT_NO_GOODS = 5;

    // 提现中
    const WITHDRAWING = 6;

    // 提现完成
    const WITHDRAWN = 7;
}