<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/22
 * Time: 20:57
 */

namespace app\api\controller\v2;


use phpmailer\EmailTo;
use think\Request;

class Emailer extends BaseController
{
    /**
     * 发送邮件
     * @param string $title 标题
     * @param string $content 内容
     */
    public function send($title = '123', $content = '4576')
    {
        EmailTo::send('912377791@qq.com', $title, $content);
    }
}