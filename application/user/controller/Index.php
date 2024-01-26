<?php

namespace app\user\controller;

use think\Controller;
use think\facade\Env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use think\Request;

class Index extends Controller {

    /**
     * @description 发送邮箱验证码
     * @api /api/index/getEmailCode
     */
    public function getEmailCode(Request $request){

        $email = $request->param('email');
        $name  = $request->param('name');

        // 创建PHPMailer实例
        $mail = new PHPMailer(true);

        try {
            // 邮件服务器设置
            $mail->isSMTP();                                      // 设置使用SMTP
            $mail->Host       = 'smtp.gmail.com';                 // 设置SMTP服务器
            $mail->SMTPAuth   = true;                             // 启用SMTP认证
            $mail->Username   = 'xiaowutongxue1234@gmail.com';    // 您的谷歌邮箱用户名
            $mail->Password   = 'peqxoeaauecavoau';               // 您的谷歌邮箱密码或应用程序专用密码
            $mail->SMTPSecure = 'tls';                            // 使用TLS加密
            $mail->Port       = 587;                              // 设置SMTP端口

            // 收件人和发件人设置
            $mail->setFrom('xiaowutongxue1234@gmail.com', 'apt');       // 设置发件人地址和姓名
            $mail->addAddress($email, $name);  // 添加收件人地址和姓名
            $code = generateRandomCode();

            // 邮件内容设置
            $mail->isHTML(true);                                  // 设置邮件格式为HTML
            $mail->Subject = 'Verification code:';              // 设置邮件主题
            $mail->Body    = 'Your verification code is(Valid within five minutes):'.$code;  // 设置邮件正文

            // 在创建实例后添加以下代码，禁用SSL证书验证
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // 发送邮件
            $mail->send();
            $redis = init_redis();
            $redis->setex($email, 3000, $code);
            echoJson(1,'The email was sent successfully');
        } catch (Exception $e) {
            echoJson(0,"The message failed to be sent： {$mail->ErrorInfo}");
        }
    }

}
