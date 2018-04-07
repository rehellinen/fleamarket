<?php
namespace app\common\model;
use enum\StatusEnum;
use think\Model;

/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 15:37
 */
class BaseModel extends Model
{
    protected $hidden = [
        'delete_time'
    ];

    // 获取没有删除的所有数据
    public function getNotDelete()
    {
        $data['status'] = array('neq', StatusEnum::Deleted);
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    // 获取审核通过的所有数据
    public function getNormal()
    {
        $data['status'] = StatusEnum::Normal;
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    // 根据id判断信息是否审核通过 / 未删除
    public function isExistedByID($id)
    {
        $data['status'] = StatusEnum::Normal;
        $data['id'] = $id;
        return $this->where($data)->find()->hidden(['listorder', 'open_id', 'status']);
    }

    // 根据id获取正常的信息
    public function getNormalById($id)
    {
        $condition['id'] = $id;
        $condition['status'] = StatusEnum::Normal;
        return $this->where($condition)->find();
    }

    // 获取正常信息的数量
    public function getNormalCount()
    {
        $condition['status'] = StatusEnum::Normal;
        return $this->where($condition)->count();
    }

    // 更新排序的方法
    public function updateListorder($id, $listorder)
    {
        $where['id'] = $id;
        $data['listorder'] = $listorder;
        return $this->where($where)->update($data);
    }

    // 更新状态的方法
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

    public function getHasImage()
    {
        $condition = [
            'status' => ['neq', StatusEnum::Deleted]
        ];
        $res = $this->where($condition)->order('listorder desc, id desc')->with('imageId')->select()->toArray();
        foreach ($res as $key => $value) {
            $res[$key]['image_id'] = $value['image_id']['image_url'];
        }
        return $res;
    }
}