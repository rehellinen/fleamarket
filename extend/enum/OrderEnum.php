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
    const UNPAID = 1;

    const PAID = 2;

    const DELIVERED = 3;

    const PAID_BUT_NO_GOODS = 4;
}