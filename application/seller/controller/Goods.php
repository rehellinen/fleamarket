<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:25
 */

namespace app\seller\controller;


use think\Request;
use think\Session;
use app\admin\controller\Base;


class Goods extends Base
{
    public function index()
    {
        $seller = Session::get('loginUser', 'seller');
        $sellerID = $seller['id'];
        $goods = (new \app\common\model\Goods())->getSellerGoods($sellerID);

        return $this->fetch('', [
            'goods' => $goods
        ]);
    }

    public function add()
    {
        $post = Request::instance()->post();
        if($post){
            $validate = validate('Goods');
            if(!$validate->scene('add')->check($post)){
                return show(0, $validate->getError());
            }
            $res = model('Goods')->save($post);
            if($res){
                return show(1,'发布商品成功！');
            }else{
                return show(0,'发布商品失败，请联系管理员');
            }
        }else{
            return $this->fetch();
        }
    }

    public function sold()
    {
        $goods = model('Goods')->getSoldGoods();
        return $this->fetch('',[
            'goods' => $goods
        ]);
    }

    public function edit()
    {
        $post = Request::instance()->post();
        if($post){
            $id = $post['id'];
            $validate = validate('Goods');
            if(!$validate->scene('edit')->check($post)){
                return show(0,$validate->getError());
            }
            $seller = Session::get('loginSeller', 'seller');
            $SessionId = $seller['id'];
            if($SessionId!=$post['seller_id']){
                return show(0,'你没有权利更改此商品');
            }

            $res = model('Goods')->updateById($id, $post);
            if($res){
                return show(1,'更新成功');
            }else{
                return show(0,'更新失败');
            }
        }else{
            $get = Request::instance()->get();
            $id = $get['id'];
            $goods = model('Goods')->get(['id'=>$id]);
            return $this->fetch('',[
                'goods' => $goods
            ]);
        }
    }
}