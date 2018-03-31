<?php
namespace phpmailer;


/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/28
 * Time: 0:57
 */
class EmailTo
{
    public static function send($receiver, $title, $content)
    {
        $mail = new Phpmailer(true); //实例化PHPMailer类,true表示出现错误时抛出异常


        $mail->IsSMTP(); // 使用SMTP

        try {
            $mail->CharSet ="UTF-8";//设定邮件编码
            $mail->Host       = "smtp.163.com"; // SMTP server
            $mail->SMTPDebug  = 1;                     // 启用SMTP调试 1 = errors  2 =  messages
            $mail->SMTPAuth   = true;                  // 服务器需要验证
            $mail->Port       = 25;					//默认端口

            $mail->Username   = config('mail.user'); //SMTP服务器的用户帐号
            $mail->Password   = config('mail.password');        //SMTP服务器的用户密码
            $mail->AddReplyTo(config('mail.user'), '网联跳蚤市场'); //收件人回复时回复到此邮箱,可以多次执行该方法
            $mail->AddAddress($receiver);//收件人如果多人发送循环执行AddAddress()方法即可 还有一个方法时清除收件人邮箱ClearAddresses()
            $mail->SetFrom(config('mail.user'), '网联跳蚤市场');//发件人的邮箱//$mail->AddAttachment('./img/bloglogo.png');      // 添加附件,如果有多个附件则重复执行该方法
            $mail->Subject = $title;

            //以下是邮件内容
            $mail->Body = $content;
            $mail->IsHTML(true);

            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //从PHPMailer捕获异常
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}