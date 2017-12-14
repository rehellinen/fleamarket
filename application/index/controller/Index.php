<?php
namespace app\index\controller;

use think\Request;

class Index extends Base
{
    public function index()
    {
        $banner = model('Banner')->where('status=1')->order('listorder desc, id desc')->limit(4)->select();
        $goods = model('Goods')->getNotSold();
        return $this->fetch('', [
            'goods' => $goods,
            'banner' => $banner
        ]);
    }

    public function detail()
    {
        $get = Request::instance()->get();
        $id = $get['id'];
        $goods = model('Goods')->get(['id'=>$id]);

        // 获取卖家信息
        $sellerId = $goods['seller_id'];
        $seller = model('Seller')->get($sellerId);


        return $this->fetch('', [
            'goods' => $goods,
            'seller' => $seller
        ]);
    }
}
