<!--作业发布后台（发邮件）-->

<?php
    // 邮件发送函数封装
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    class send_email_class {
        static function send_email ($json, $sender, $sendername, $receiver, $receivername, $response, $title, $content, $nothtml) {
            include_once('hw-includes/email/src/Exception.php') ;
            include_once('hw-includes/email/src/PHPMailer.php') ;
            include_once('hw-includes/email/src/SMTP.php') ;
            $mail = new PHPMailer(true); 
            $email_config = json_decode($json);
            try {
                $mail->CharSet = "UTF-8";                     //设定邮件编码
                $mail->SMTPDebug = 0;                        // 调试模式输出
                $mail->isSMTP();                             // 使用SMTP
                $mail->Host = $email_config->Host;                // SMTP服务器
                $mail->SMTPAuth = true;                      // 允许 SMTP 认证
                $mail->Username = $email_config->Username;                // SMTP 用户名  即邮箱的用户名
                $mail->Password = $email_config->Password;             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
                $mail->SMTPSecure = $email_config->SMTPSecure;                    // 允许 TLS 或者ssl协议
                $mail->Port = $email_config->Port;                            // 服务器端口 25 或者 465 具体要看邮箱服务器支持
                $mail->setFrom($sender, $sendername);  //发件人
                $mail->addAddress($receiver, $receivername);  // 收件人
                //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
                $mail->addReplyTo($response, $sendername); //回复的时候回复给哪个邮箱 建议和发件人一致
                //$mail->addCC('cc@example.com');                    //抄送
                //$mail->addBCC('bcc@example.com');                    //密送
            
                //发送附件
                // $mail->addAttachment('../xy.zip');         // 添加附件
                // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名
            
                // Content
                $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
                $mail->Subject = $title;
                $mail->Body    = $content;
                $mail->AltBody = $nothtml;
            
                $mail->send();
                return "succeed";
            } 
            catch (Exception $e) {
                echo $e;
            }
        }
    }
?>
