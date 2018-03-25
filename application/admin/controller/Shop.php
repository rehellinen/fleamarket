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

    public function add()
    {
        $post = Request::instance()->post();
        if($post){
            // 对上传图片的处理
            $image = Image::create(['image_url' => $post['main_image_id']]);
            $post['main_image_id'] = $image->id;
            $image = Image::create(['image_url' => $post['top_image_id']]);
            $post['top_image_id'] = $image->id;
            $image = Image::create(['image_url' => $post['avatar_image_id']]);
            $post['avatar_image_id'] = $image->id;

            $res = model('shop')->insert($post);
            if($res){
                return show(1,'新增成功');
            }else{
                return show(0,'新增失败');
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit()
    {
        $post = Request::instance()->post();
        if($post){
            // 对上传图片的处理
            $pattern = '{/upload/\w+.png}';
            preg_match($pattern, $post['main_image_id'], $match1);
            preg_match($pattern, $post['top_image_id'], $match2);
            preg_match($pattern, $post['avatar_image_id'], $match3);

            $post['main_image_id'] = $match1[0];
            $post['top_image_id'] = $match2[0];
            $post['avatar_image_id'] = $match3[0];

            $image = Image::create(['image_url' => $post['main_image_id']]);
            $post['main_image_id'] = $image->id;
            $image = Image::create(['image_url' => $post['top_image_id']]);
            $post['top_image_id'] = $image->id;
            $image = Image::create(['image_url' => $post['avatar_image_id']]);
            $post['avatar_image_id'] = $image->id;

            $result = model('shop')->where('id='.$post['id'])->update($post);
            if($result){
                return show(1,'更新成功');
            }else{
                return show(0,'更新失败');
            }

        }else{
            $id = $_GET['id'];
            $result = model('shop')->get($id);
            return $this->fetch('', [
                'res' => $result
            ]);
        }
    }
}