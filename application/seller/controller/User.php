<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 10:40
 */

namespace app\seller\controller;


use app\admin\controller\Base;

class User extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function password()
    {
        return $this->fetch();
    }
}