<?php
namespace app\common\model;
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 15:36
 */
class Seller extends Base
{
    public function getSellerByName($name, $status = 1)
    {
        $data = [
            'name' => $name,
            'status' => $status
        ];
        return $this->where($data)->find();
    }

    public function insertSeller($data)
    {
        //生成加盐字符串
        $salt = substr(md5(time()),10,5);
        $md5Pwd = md5(config('admin.md5_prefix').$data['password'].$salt);
        $data['code'] = $salt;
        $data['password'] = $md5Pwd;
        return $this->save($data);
    }

    public function getSellerByTele($tele)
    {
        $data = array(
            'tele' => $tele,
            'status' => 1
        );

        return $res = $this->where($data)->find();
    }



    public function getRootByTele($tele)
    {
        $data = array(
            'tele' => $tele,
            'status' => 1,
            'is_root' => 1,
        );

        return $this->where($data)->find();
    }

    public function getSeller($status)
    {
        $data['status'] = $status;
        $data['is_root'] = 0;
        return $this->where($data)->order('listorder desc, id desc')->paginate(10);
    }

    public function getSellerCount()
    {
        $data['status'] = 0;
        return $this->where($data)->count();
    }
}