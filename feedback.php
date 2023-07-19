<!--DHW 每日作业（意见反馈）-->

<?php
    require_once("functions.php");
    require_once("send_email.php");
?>
<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>使用体验反馈 —— 作业发布后台</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
    </head>
    
    <body>
        <div id="modal_div"></div>
        <?php
            if (isset($_POST["submit"]))
            {
                $name = $_POST["name"];
                $contact = $_POST["contact"];
                $feedback = $_POST["feedback"];
                
                $json = '{"Host":"smtp.qq.com","Username":"2471963891@qq.com","Password":"vffsjmxuqggudhgb","SMTPSecure":"ssl","Port":"465"}';
                $title = "有新的使用体验反馈 —— DHW 每日作业";
                $content = "<h1>有新的使用体验反馈</h1>" . 
                        "<p>反馈人昵称：" . $name . "</p>" . 
                        "<p>反馈人联系方式：" . $contact . "</p>" . 
                        "<p>反馈内容：" . $feedback . "</p>" . 
                        "❤️~";
                send_email_class::send_email($json, "2471963891@qq.com", "DHW 每日作业", "2471963891@qq.com", "DHW 每日作业", "2471963891@qq.com", $title, $content, $content);
                echo "<script>showModal('success', '您的反馈已发送至开发者邮箱，预计 1-3 日内收到回复，感谢您对 DHW 每日作业的支持！', 'backstage.php')</script>";
            }
        ?>
    </body>
</html>
