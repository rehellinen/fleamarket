<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/1/22
 * Time: 19:33
 */

namespace app\api\controller\v1;


use think\Controller;

class Token extends Controller
{
    public function getToken($code = '')
    {
        return 1;
    }
}