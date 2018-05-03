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
use think\Request;

class Image extends BaseController
{
    protected $beforeActionList = [
        'checkSellerShopScope' => ['only' => 'imageUpload'],
        'checkShopScope' => ['only' => 'appUpload']
    ];

    public function saveQRCode($content)
    {

    }

    /**
     * 上传图片(不带压缩功能)
     * @throws SuccessMessage
     */
    public function imageUpload()
    {
        $image = Request::instance()->file('image');
        $info = $image->move(ROOT_PATH . 'public' . DS . 'upload');
        $path = '/upload/' . $info->getSaveName();
        $imageID = (new ImageModel)->insertGetId(['image_url' => $path]);
        throw new SuccessMessage([
            'message' => '上传图片成功',
            'data' => ['image_id' => $imageID]
        ]);
    }

    /**
     * 商店头图以及头像的上传 / 更改
     * @param string $type 判断是头图还是头像
     * @throws SuccessMessage
     */
    public function appUpload($type)
    {
        $image = Request::instance()->file('image');
        $info = $image->move(ROOT_PATH . 'public' . DS . 'upload');
        $path = '/upload/' . $info->getSaveName();

        $shopID = TokenService::getCurrentTokenVar('shopID');
        $imageID = (new ImageModel)->insertGetId(['image_url' => $path]);
        $res = (new Shop())->save([$type => $imageID], ['id' => $shopID]);

        if($res){
            throw new SuccessMessage([
                'message' => '更改图片成功',
                'data' => ['image_id' => $imageID]
            ]);
        }
    }

    /**
     * CMS 使用的上传图片方法
     * @return \think\response\Json
     */
    public function upload()
    {
        $path = $this->resizePhoto();

        if($path) {
            return show(1, '成功', $path);
        } else {
            return show(0, '失败');
        }
    }

    /**
     * 对图片进行压缩
     * @param string $setWidth 图片最大宽度
     * @param string $setHeight 图片最大高度
     * @return string
     */
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

    /**
     * 生成图片文件名
     * @param $char
     * @return string
     */
    private function getMD5Name($char)
    {
        $time = time();
        $md5Str = md5(config('admin.md5_prefix').$time.$char);
        return $md5Str.'.png';
    }
}