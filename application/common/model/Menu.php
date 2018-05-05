<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/22
 * Time: 10:18
 */

namespace app\common\model;


use enum\StatusEnum;

class Menu extends BaseModel
{
    // 获取父菜单
    public function getParentMenu($status = [0, 1])
    {
        $data = [
            'status' => ['in', $status],
            'parent_id' => 0
        ];
        return $this->where($data)->order('listorder desc, id desc')->select();
    }

    // 获取所有子菜单
    public function getChildMenu($status = [0, 1])
    {
        $data = [
            'status' => ['in', $status],
            'parent_id' => ['neq', 0]
        ];
        return $this->where($data)->order('listorder desc, id desc')->select();
    }

    public function getChildMenuByID($id)
    {
        $data = [
            'status' => array('neq', StatusEnum::DELETED),
            'parent_id' => $id
        ];
        return $this->where($data)->order('listorder desc, id desc')->paginate();
    }
}