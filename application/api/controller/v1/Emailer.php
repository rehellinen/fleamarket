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
    public function Send()
    {
        $post = Request::instance()->post();
        $title = '跳蚤市场审核提醒';
        $content = "商家<<".$post['name'].">>提交了跳蚤市场商家申请，请及时审核";
        EmailTo::send(config('mail.mailTo'), $title, $content);

        return show(1, '成功');
    }
}