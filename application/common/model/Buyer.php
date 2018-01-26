<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/29
 * Time: 17:25
 */

namespace app\common\model;


class Buyer extends Base
{
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

    public function getBuyer($status)
    {
        $data['status'] = $status;
        return $this->where($data)->order('listorder desc, id desc')->paginate(10);
    }

    public function getBuyerCount()
    {
        $data['status'] = 1;
        return $this->where($data)->count();
    }

    public function getByOpenID($openID)
    {
        $buyer = self::where('openid', '=', $openID)->find();
        return $buyer;
    }
}