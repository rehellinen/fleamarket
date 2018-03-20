<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/21
 * Time: 17:26
 */

namespace app\common\validate;


use app\common\exception\ParameterException;
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
            throw new ParameterException([
                'message' => $this->error
            ]);
        }

        return true;
    }

    public function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if(empty($value)) {
            return false;
        }else{
            return true;
        }
    }
}