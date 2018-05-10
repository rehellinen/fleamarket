<?php
namespace app\common\model;
use enum\StatusEnum;
use enum\TypeEnum;
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

    public function getNormalShopOrSeller()
    {
        $cond = [
            'status' => StatusEnum::NORMAL
        ];
        $newGoods = [];
        $oldGoods = [];
        $shop = (new Shop())->where($cond)->select()->toArray();
        foreach($shop as $k => $value){
            array_push($newGoods, $value['id']);
        }

        $seller = (new Seller())->where($cond)->select()->toArray();
        foreach($seller as $k => $value){
            array_push($oldGoods, $value['id']);
        }

        return '((  `type` = 2  AND `foreign_id` IN (65) ) or (`type` = 1  AND `foreign_id` IN (64)))';
    }

    // 获取没有删除的所有数据
    public function getNotDelete()
    {
        $data['status'] = array('neq', StatusEnum::DELETED);
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    // 获取审核通过的所有数据
    public function getNormal()
    {
        $data['status'] = StatusEnum::NORMAL;
        $order = array(
            'listorder' => 'desc',
            'id' => 'desc'
        );
        return $this->where($data)->order($order)->paginate();
    }

    /**
     * 根据ID获取状态为1的纪录
     * @param int $id 主键
     * @return Model
     */
    public function getNormalById($id)
    {
        $condition['id'] = $id;
        $condition['status'] = StatusEnum::NORMAL;
        return $this->where($condition)->find();
    }

    // 获取正常信息的数量
    public function getNormalCount()
    {
        $condition['status'] = StatusEnum::NORMAL;
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

    // 获取有头图的数据
    public function getHasImage()
    {
        $condition = [
            'status' => ['neq', StatusEnum::DELETED]
        ];
        $res = $this->where($condition)->order('listorder desc, id desc')->with('imageId')->select()->toArray();
        foreach ($res as $key => $value) {
            $res[$key]['image_id'] = $value['image_id']['image_url'];
        }
        return $res;
    }
}