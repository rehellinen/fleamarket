<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/11/22
 * Time: 20:57
 */

namespace app\api\controller\v1;


use phpmailer\EmailTo;
use think\Request;

class Emailer extends BaseController
{
    /**
     * 发送邮件
     * @param string $title 标题
     * @param string $content 内容
     */
    public function Send($title, $content)
    {
        EmailTo::send(config('mail.mailTo'), $title, $content);
    }
}