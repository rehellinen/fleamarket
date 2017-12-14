<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/21
 * Time: 17:26
 */

namespace app\common\validate;


use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene)
    {
        // 获取所有参数
        $params = Request::instance()->param();

        // 校验
        $result = $this->scene($scene)->check($params);

        // 抛出异常
        if(!$result) {
            return show(0, $this->error);
            json_last_error_msg();
        }

        return true;
    }

    protected function isNotEmpty($value)
    {
        if(empty($value)) {
            return false;
        }

        return true;
    }
}