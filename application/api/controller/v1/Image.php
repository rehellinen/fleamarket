<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 0:34
 */

namespace app\api\controller\v1;

use app\common\exception\SuccessMessage;
use app\common\service\Token as TokenService;
use app\common\model\Image as ImageModel;
use app\common\model\Shop;
use app\common\model\Seller;
use think\Request;

class Image extends BaseController
{
    public function appUpload($type)
    {
        $image = Request::instance()->file('image');
        $info = $image->move(ROOT_PATH . 'public' . DS . 'upload');
        $path = '/upload/' . $info->getSaveName();

        $sellerID = TokenService::getCurrentTokenVar('sellerID');
        $shopID = TokenService::getCurrentTokenVar('shopID');
        $imageID = (new ImageModel)->insertGetId(['image_url' => $path]);
        if ($sellerID){
            (new Seller())->save([$type => $imageID], ['id' => $sellerID]);
        }elseif ($shopID){
            (new Shop())->save([$type => $imageID], ['id' => $shopID]);
        }
        throw new SuccessMessage([
            'message' => '更改图片成功'
        ]);
    }

    public function upload()
    {
        $path = $this->resizePhoto();

        if($path) {
            return show(1, '成功', $path);
        } else {
            return show(0, '失败');
        }
    }

    private function resizePhoto($setWidth = '800', $setHeight = '400')
    {
        // 获取图片相关信息
        $file = Request::instance()->file('file');
        $image = \think\Image::open($file);
        $width = $image->width();
        $height = $image->height();

        // 处理
        $ratio = $width / $height;

        $basePath = config('upload_file');

        if($width > $height) {
            // 宽大于高的情况
            $name = $this->getMD5Name('width');
            $image->thumb(($setHeight * $ratio), $setHeight)->save($basePath.$name);
        }else{
            // 高大于宽的情况
            $name = $this->getMD5Name('height');
            $image->thumb($setWidth, ($setWidth / $ratio))->save($basePath.$name);
        }

        return '/'.$basePath.$name;
    }

    private function getMD5Name($char)
    {
        $time = time();
        $md5Str = md5(config('admin.md5_prefix').$time.$char);
        return $md5Str.'.png';
    }
}