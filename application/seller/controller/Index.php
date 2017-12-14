<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/27
 * Time: 23:35
 */

namespace app\seller\controller;


use app\admin\controller\Base;
use phpmailer\EmailTo;

class Index extends Base
{
    public function index()
    {
        $user = $this->getLoginUser();
        $uid = $user['id'];

        // 获取发布的商品
        $condition = array(
            'seller_id' => $uid,
            'status' => 1
        );
        $goodsCount = model('Goods')->where($condition)->count();

        return $this->fetch('', [
            'goodsCount' => $goodsCount
        ]);
    }

    public function email()
    {
        EmailTo::send('912377791@qq.com', '跳蚤市场测试', '内容内容内容内容内容内容内容内容内容');
    }
}