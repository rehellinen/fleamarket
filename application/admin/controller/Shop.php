<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2018/3/25
 * Time: 10:45
 */

namespace app\admin\controller;

use app\common\model\Shop as ShopModel;
use think\Request;
use app\common\model\Image;

class Shop extends BaseController
{
    public function index()
    {
        $shop = (new ShopModel())->getNotDelete();
        return $this->fetch('', [
            'shop' => $shop
        ]);
    }

    public function edit()
    {
        $post = Request::instance()->post();
        if($post){
            $result = model('shop')->where('id='.$post['id'])->update($post);
            if($result){
                return show(1,'更新成功');
            }else{
                return show(0,'更新失败');
            }

        }else{
            $id = $_GET['id'];
            $result = (new \app\common\model\Shop())->getAdminShop($id);
            return $this->fetch('', [
                'res' => $result
            ]);
        }
    }
}