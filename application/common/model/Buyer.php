<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 17:25
 */

namespace app\common\model;


class Buyer extends BaseModel
{
    public function address()
    {
        return $this->hasOne('BuyerAddress', 'buyer_id', 'id');
    }

    // 方法1：根据open_id获取用户信息
    public function getByOpenID($openid)
    {
        return $this->where(['open_id' => $openid])->find();
    }

    public function insertBuyer($data)
    {
        $data['status'] = 1;
        //生成加盐字符串
        $salt = substr(md5(time()),10,5);
        $md5Pwd = md5(config('admin.md5_prefix').$data['password'].$salt);
        $data['code'] = $salt;
        $data['password'] = $md5Pwd;
        return $this->save($data);
    }

    public function getBuyerByTele($tele)
    {
        $data = array(
            'tele' => $tele,
            'status' => 1
        );

        return $res = $this->where($data)->find();
    }

    public function getBuyerCount()
    {
        $data['status'] = 1;
        return $this->where($data)->count();
    }
}