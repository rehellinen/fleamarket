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

    public function getDataByRule($data)
    {
        if(array_key_exists('buyerID', $data) || array_key_exists('SellerID', $data)){
            throw new ParameterException([
                'message' => '参数中包含buyerID或者sellerID'
            ]);
        }
        $newData = [];
        foreach ($this->rule as $key => $value)
        {
            $newData[$value[0]] = $data[$value[0]];
        }
        return $newData;
    }
}