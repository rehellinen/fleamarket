<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 13:25
 */

namespace app\admin\controller;

use think\Request;
use think\Session;


class Goods extends Base
{
    public function index()
    {
        $goods = model('Goods')->getGoods(1);
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
        $goods = model('Goods')->getGoods(2);
        return $this->fetch('',[
            'goods' => $goods
        ]);
    }


    public function down()
    {
        $goods = model('Goods')->getGoods(-1);
        return $this->fetch('',[
            'goods' => $goods
        ]);
    }
}