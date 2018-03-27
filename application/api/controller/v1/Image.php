<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/30
 * Time: 0:34
 */

namespace app\api\controller\v1;


use app\api\controller\v1\BaseController;
use think\Request;

class Image extends BaseController
{
    public function upload()
    {
        $path = $this->resizePhoto();

        if($path) {
            return show(1, '成功', $path);
        } else {
            return show(0, '失败');
        }
    }

    private function resizePhoto($setWidth = '600', $setHeight = '330')
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