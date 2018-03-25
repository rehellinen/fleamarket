<?php
namespace app\common\model;
use think\Model;

/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 15:37
 */
class BaseModel extends Model
{
    // 获取没有删除的所有数据
    public function getNotDelete()
    {
        $data['status'] = array('neq', -1);
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    // 获取审核通过的所有数据
    public function getNormal()
    {
        $data['status'] = 1;
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    // 根据id判断信息是否审核通过 / 未删除
    public static function isExistedByID($id)
    {
        $data['status'] = 1;
        $data['id'] = $id;
        return self::where($data)->find();
    }

    // 根据id获取正常的信息
    public function getNormalById($id)
    {
        $condition['id'] = $id;
        $condition['status'] = 1;
        return $this->where($condition)->find();
    }

    public function updateListorder($id, $listorder)
    {
        $where['id'] = $id;
        $data['listorder'] = $listorder;
        return $this->where($where)->update($data);
    }

    public function updateStatus($id, $status)
    {
        $data['status'] = $status;
        return $this->where('id='.$id)->update($data);
    }

    public function updateById($id, $data)
    {
        $condition['id'] = $id;
        return $this->where($condition)->update($data);
    }
}