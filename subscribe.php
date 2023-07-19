<!--作业发布网站（订阅）-->

<?php
    require_once("config.php");
    require_once("send_email.php");
    require_once("functions.php");
?>

<?php
    // 连接数据库
    date_default_timezone_set("PRC");    // 时区
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("连接失败：" . $conn->connect_error);
?>

<?php
    if ($semail == '')
        echo "<center><h1 style='padding-top: 20%'>该网站未配置 SMTP 服务，暂不支持订阅！</h1></center>";
    else {
?>

<!DOCTYPE html>
<html>
    <head>
        <title>订阅 - 每日作业发布</title>
        <link rel="stylesheet" href="hw-includes/node_modules/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="hw-css/subscribe.css">
    </head>
    
    <body>
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
        
            @media (min-width: 768px) {
                    .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
    </style>
        
        <?php
            if (isset($_GET["state"]) && $_GET["state"] == "error")
                echo "<script>alert('订阅失败：昵称和邮箱不能为空。');</script>";
            if (isset($_GET["state"]) && $_GET["state"] == "exist")
            {
                $nickname = $_GET["nn"];
                echo "<script>alert('订阅失败：该邮箱已订阅。');</script>";
            }
            if (isset($_GET["state"]) && $_GET["state"] == "back")
            {
                $nickname = $_GET["nn"];
                $receiver = $_GET["r"];
            }
            if (isset($_GET["state"]) && $_GET["state"] == "succeed" && isset($_GET["yesnn"]) && isset($_GET["yesr"]))
            {
                $nickname = $_GET["yesnn"];
                $receiver = $_GET["yesr"];
                setcookie("verify_code", "");
                $time = date("Y-m-d H:i:s");
                // 再次确认是否有重复项（防止 sql 注入）
                $sql = "SELECT * FROM {$prefix}Subscribers WHERE email='{$receiver}'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0)
                    header("Location: index.php");
                $sql = "INSERT INTO {$prefix}Subscribers (name, email, is_receive, time) VALUES ('{$nickname}', '{$receiver}', '1', '{$time}')";
                $conn->query($sql);
                echo "<script>alert('订阅成功！请查看你的邮箱~');</script>";
                // 发送订阅成功邮件
                $emailtitle = "订阅成功 —— 订阅每日作业";    // 邮件主题
                $content = 
                "<html>
                    <head>
                        <meta charset='utf-8'>
                    </head>
                    
                    <body>
                        <center>
                            <div style='background-color: #fff6f6; padding: 20px; margin: auto; margin-top: 20px;'>
                            <img src='$setup_link/hw-imgs/logo.jpg' style='height: 96px; width: 96px;'>
                            <h1>【订阅成功！】</h1>
                            <p>嗨！$nickname<span>~</span></p>
                            <p>你已经成功订阅了陈分初二（5）班的每日作业！</p>
                            <p>以后的每一天，你都会收到专属的作业提醒，注意查收呀~</p>
                            <p>若非你本人操作，你的邮箱账号密码可能已经泄露，请及时关注。</p>
                            <p>$footer<p>
                            <p><span>——</span>$love_thanks<span>——</span></p>
                            </div>
                        </center>
                    </body>
                </html>";
                $nohtml = "嗨！". $nickname .
                        "~<br>你已经成功订阅了陈分初二（5）班的每日作业！<br>以后的每一天，你都会收到专属的作业提醒，注意查收呀~<br>若非你本人操作，你的邮箱账号密码可能已经泄露，请及时关注。<br>——Powered by LOVE ❤️.——";
                send_email_class::send_email($email_json, $semail, $sname, $receiver, $nickname, $remail, $emailtitle, $content, $nohtml);
                // 发邮件通知网站管理员有新的订阅者
                $emailtitle = "有新的订阅者 —— 每日作业发布";
                $content = 
                "<html>
                    <head>
                        <meta charset='utf-8'>
                    </head>
                    
                    <body>
                        <h2>$title<span>有新的订阅者</span></h2>
                        <p><span>订阅者昵称：</span>$nickname</p>
                        <p><span>订阅者邮箱：</span>$receiver</p>
                        <p><span>订阅时间：</span>$time</p>
                        <p>❤️</p>
                    </body>
                <html>";
                send_email_class::send_email($email_json, $semail, $sname, $semail, $semail, $remail, $emailtitle, $content, $content);
                echo "<script>window.location.href='index.php';</script>";
            }
            if (isset($_GET["state"]) && $_GET["state"] == "fail")
            {
                $nickname = $_GET["nn"];
                $receiver = $_GET["r"];
                echo "<script>alert('订阅失败：验证码错误。');</script>";
            }
            if (isset($_POST["subscribe"]))
            {
                $nickname = $_POST["nickname"];
                $receiver = $_POST["receiver"];
                $user_vrf = $_POST["code"];
                $answer = $_POST["random_code"];
                if ($user_vrf == $_COOKIE["verify_code"])
                    header("Location: subscribe.php?state=succeed&yesnn=$nickname&yesr=$receiver");
                else
                    header("Location: subscribe.php?state=fail&nn=$nickname&r=$receiver");
            }
            if (isset($_POST["verify"]))
            {
                $nickname = $_POST["nickname"];
                $receiver = $_POST["email"];
                $sql = "SELECT * FROM {$prefix}Subscribers WHERE email='{$receiver}'";
                $result = $conn->query($sql);
                if ($nickname == null || $receiver == null)
                    header("Location: subscribe.php?state=error");
                elseif ($result->num_rows > 0)
                    header("Location: subscribe.php?state=exist&nn=$nickname");
                else {
                    $emailtitle = "验证码 —— 订阅每日作业";    // 邮件主题
                    $random = rand(100000, 999999);    // 验证码
                    setcookie("verify_code", $random, time()+60*5);    // cookie 存储验证码，时效五分钟
                    $content = 
                    "<html>
                        <head>
                            <meta charset='utf-8'>
                        </head>
                        
                        <body>
                            <center>
                                <div style='background-color: #ebfcff; padding: 20px; margin: auto; margin-top: 20px;'>
                                <img src='$setup_link/hw-imgs/logo.jpg' style='height: 96px; width: 96px;'>
                                <h1><span>【</span>$random<span>】</span></h1>
                                <p>嗨！$nickname<span>~</span></p>
                                <p>你的验证码是：$random<span>。</span></p>
                                <p>该验证码 5 分钟之内有效。（如发送了新的验证码，该验证码将失效，以保护订阅者邮箱账户安全。）</p>
                                <p>若非你本人操作，请忽略~</p><br>
                                <p>$footer<p>
                                <p><span>——</span>$love_thanks<span>——</span></p>
                                </div>
                            </center>
                        </body>
                    </html>";
                    $nohtml = "【" . $random. "】嗨！". $nickname .
                            "~<br>你的验证码是" . $random . 
                            "。<br>该验证码 5 分钟之内有效。（如发送了新的验证码，该验证码将失效，以保护订阅者邮箱账户安全。）<br>若非你本人操作，请忽略~";
                    send_email_class::send_email($email_json, $semail, $sname, $receiver, $nickname, $remail, $emailtitle, $content, $nohtml);
        ?>
        <div class="form-subscribe">
            <form action="subscribe.php" method="post">
                <center><img class="mb-4" src="hw-imgs/logo.jpg" alt="" width="96" height="96"></center>
                <h1 class="h3 mb-3 fw-normal"><center>订阅每日作业</center></h1>
                <p>验证码已发送至 <?php echo $receiver;?>，有效时间五分钟。（如发送了新的验证码，该验证码将失效，以保护订阅者邮箱账户安全。）</p>
                <input type="hidden" name="random_code" value=<?php echo $random;?>>
                <input type="hidden" name="nickname" value=<?php echo $nickname;?>>
                <input type="hidden" name="receiver" value=<?php echo $receiver;?>>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingCode" name="code" placeholder="code">
                    <label for="floatingInput">验证码</label>
                </div>
                <br>
                <button class="w-100 btn btn-lg btn-primary" type="submit" name="subscribe">订阅</button>
                <p class="mt-3 mb-3">
                    <a href="subscribe.php?state=back&nn=<?php echo $nickname;?>&r=<?php echo $receiver;?>" class="text-muted">&larr; 返回</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php" class="text-muted">&larr; 返回首页</a>
                </p>
                <p class="mt-5 mb-3 text-muted">&copy; <?php echo $all_years;?> <?php echo $class_short_name;?></p>
            </form>
        </div>
        <?php
            }
        }
        else {
        ?>
        <div class="form-subscribe">
            <form action="subscribe.php" method="post">
                <center><img class="mb-4" src="hw-imgs/logo.jpg" alt="" width="96" height="96"></center>
                <h1 class="h3 mb-3 fw-normal"><center>订阅每日作业</center></h1>
                <p>订阅后，系统将自动将每天的作业发送到你的邮箱~</p>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="nickname" placeholder="nickname" value=<?php echo $nickname;?>>
                    <label for="floatingInput">昵称（不支持特殊符号）</label>
                </div>
                <br>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="email" value=<?php echo $receiver;?>>
                    <label for="floatingEmail">邮箱</label>
                </div>
                <br>
                <button class="btn btn-warning" style="float: right;" type="submit" name="verify">发送验证码</button>
                <p class="mt-3 mb-3"><a href="index.php" class="text-muted">&larr; 返回首页</a></p>
                <p class="mt-5 mb-3 text-muted">&copy; <?php echo $all_years;?> <?php echo $class_short_name;?></p>
            </form>
        </div>
        <?php
            }
        ?>
    </body>
</html>

<?php
}
?>