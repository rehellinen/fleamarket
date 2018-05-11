<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 13:54
 */

namespace app\admin\controller;

use app\common\model\Order as OrderModel;

class Order extends BaseController
{
    public function index()
    {
        $order = (new \app\common\model\Order())->getNotDelete();
        return $this->fetch('',[
            'order' => $order
        ]);
    }

    public function withdraw()
    {
        $post = $this->request->post();
        $id = $post['id'];
        $status = $post['status'];

        $res = (new OrderModel)->updateStatus($id, $status);
        if($res){
            return show(1, '提现成功');
        }else{
            return show(0, '提现失败');
        }
    }
}