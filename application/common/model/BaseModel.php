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
    public function getNotDelete()
    {
        $data['status'] = array('neq', -1);
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    public function getNormal()
    {
        $data['status'] = array('neq', -1);
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
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

    public function getById($id)
    {
        $condition['id'] = $id;
        return $this->where($condition)->find();
    }
}