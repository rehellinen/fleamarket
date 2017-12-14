<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/22
 * Time: 13:59
 */

namespace app\index\controller;


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