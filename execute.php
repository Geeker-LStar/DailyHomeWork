<!--作业发布后台（数据处理）-->

<?php
    ignore_user_abort(true); // 后台运行
    set_time_limit(0); // 取消脚本运行时间的超时上限
?>

<?php
    session_start();
?>

<?php
    // 检查是否登录，未登录则要求登录
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<?php
    require_once("config.php");    // 配置文件
    require_once("send_email.php");    // 邮件发送
    require_once("functions.php");    // 重要函数
?>

<?php
    // 连接数据库
    date_default_timezone_set("PRC");    // 时区
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("连接失败：" . $conn->connect_error);
?>

<title>数据处理 —— 作业发布后台</title>
<div id="modal_div"></div>

<?php
    if (isset($_POST["publish"]) || isset($_POST["modify"]))
    {   
        // 获取类型（发布 or 修改）
        $type = $_POST["type"];
        // 获取数据表名和日期
        $post_tablen = $_POST["tablen"];
        $tablen = $prefix . $post_tablen;    // 带有表前缀的表名称
        $date = $_POST["date"];
        // 判断数据表是否存在
        $sql = "SHOW TABLES LIKE '{$tablen}'";
        $result = $conn->query($sql);
        $exist = $result->num_rows;
        // 不存在则创建
        if ($exist == 0)
        {
            $sql  = "CREATE TABLE `{$dbname}`.`{$tablen}` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键' , 
                `date` VARCHAR(10) NOT NULL COMMENT '日期' , 
                `Chinese` VARCHAR(1024) NOT NULL COMMENT '语文' , 
                `Math` VARCHAR(1024) NOT NULL COMMENT '数学' , 
                `English` VARCHAR(1024) NOT NULL COMMENT '英语' , 
                `Physics` VARCHAR(1024) NOT NULL COMMENT '物理' , 
                `Biology` VARCHAR(1024) NOT NULL COMMENT '生物' , 
                `Geography` VARCHAR(1024) NOT NULL COMMENT '地理' , 
                `History` VARCHAR(1024) NOT NULL COMMENT '历史' , 
                `Politics` VARCHAR(1024) NOT NULL COMMENT '道法' , 
                `Chemistry` VARCHAR(1024) NOT NULL COMMENT '化学' , 
                `More` VARCHAR(1024) NOT NULL COMMENT '其他' , 
                PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '$post_tablen 的作业';";
            $conn->query($sql);
            echo(mysqli_error($conn));
        }
        // 获取作业
        $yw = $_POST["yw"];
        $yw = textarea_line_break($yw);
        $sx = $_POST["sx"];
        $sx = textarea_line_break($sx);
        $yy = $_POST["yy"];
        $yy = textarea_line_break($yy);
        $wl = $_POST["wl"];
        $wl = textarea_line_break($wl);
        $hx = $_POST["hx"];
        $hx = textarea_line_break($hx);
        $sw = $_POST["sw"];
        $sw = textarea_line_break($sw);
        $dl = $_POST["dl"];
        $dl = textarea_line_break($dl);
        $ls = $_POST["ls"];
        $ls = textarea_line_break($ls);
        $df = $_POST["df"];
        $df = textarea_line_break($df);
        $qt = $_POST["qt"];
        $qt = textarea_line_break($qt);
        
        $time = $post_tablen . "_" . $date;
        $yw2 = "<h3>语文</h3>" . $yw;
        $sx2 = "<h3>数学</h3>" . $sx;
        $yy2 = "<h3>英语</h3>" . $yy;
        $wl2 = "<h3>物理</h3>" . $wl;
        $hx2 = "<h3>化学</h3>" . $hx;
        $sw2 = "<h3>生物</h3>" . $sw;
        $dl2 = "<h3>地理</h3>" . $dl;
        $ls2 = "<h3>历史</h3>" . $ls;
        $df2 = "<h3>道法</h3>" . $df;
        $qt2 = "<h3>其它</h3>" . $qt;
        
        // 发布作业
        if ($type == "publish")
        {
            $sql = "INSERT INTO $tablen (date, Chinese, Math, English, Physics, Biology, Geography, History, Politics, Chemistry, More) VALUES ('{$date}', '{$yw}', '{$sx}', '{$yy}', '{$wl}', '{$sw}', '{$dl}', '{$ls}', '{$df}', '{$hx}', '{$qt}')";
            $conn->query($sql);
            if (mysqli_error($conn))
                echo mysqli_error($conn);
            else
                echo "<script>showModal('success', '发布作业成功！<a href=\"hw.php?type=show%26time=$post_tablen\" class=\"text-blue-500 block my-2\">去查看</a>复制链接：$setup_link/hw.php?type=show%26time=$post_tablen', 'hw.php?type=show%26time=$post_tablen')</script>";
            // 查找今天的作业并判断是否没有某科目的作业，如果没有，就不加入到邮件正文中
            $sql = "SELECT * FROM $tablen WHERE date='{$date}'";
            $result = $conn->query($sql);
            $all_subject = $result->fetch_assoc();
            foreach ($all_subject as $key => $value)
            {
                if ($key != "id" && $key != "date" && $value != null && $value != "无")
                {
                    $content = $content . "<h3>" . $translation[$key] . "</h3>" .
                            "<div style='line-height: 2'>" . $value . "</div>";
                }
            }
            
            $content = $content . "<hr>" . $footer . "<br>" . $love_thanks;
            $title = $time . " 的作业";    // 邮件主题
            $sql = "SELECT * FROM {$prefix}Subscribers";
            $result = $conn->query($sql);
            // 循环，给每一个订阅者发送邮件
            if ($result->num_rows > 0)
            {
                while ($arr = $result->fetch_assoc())
                {
                    $nickname = $arr["name"];
                    $receiver = $arr["email"];
                    $content2 = "<img src='$setup_link/hw-imgs/logo.jpg' style='height: 96px; width: 96px;'>
                    <h2>$time <span>的作业</span></h2>
                    嗨~" . $nickname . "~<br>以下是今天的作业：" . $content;
                    // 发送作业邮件
                    send_email_class::send_email($email_json, $semail, $sname, $receiver, $nickname, $remail, $title, $content2, $content);
                }
            }
        }
        if ($type == "modify")
        {   
            $sql = "UPDATE $tablen SET Chinese='{$yw}', Math='{$sx}', English='{$yy}', Physics='{$wl}', Biology='{$sw}', Geography='{$dl}', History='{$ls}', Politics='{$df}', Chemistry='$hx', More='{$qt}' WHERE date='{$date}'";
            $conn->query($sql);
            if (mysqli_error($conn))
                echo mysqli_error($conn);
            else
                echo "<script>showModal('success', '修改作业成功！<a href=\"hw.php?type=show%26time=$post_tablen\" class=\"text-blue-500 block my-2\">去查看</a>复制链接：$setup_link/hw.php?type=show%26time=$post_tablen', 'hw.php?type=show%26time=$post_tablen')</script>";
                
            // 查找今天的作业并判断是否没有某科目的作业，如果没有，就不加入到邮件正文中
            $sql = "SELECT * FROM $tablen WHERE date='{$date}'";
            $result = $conn->query($sql);
            $all_subject = $result->fetch_assoc();
            foreach ($all_subject as $key => $value)
            {
                if ($key != "id" && $key != "date" && $value != null && $value != "无")
                {
                    $content = $content . "<h3>" . $translation[$key] . "</h3>" .
                            "<div style='line-height: 2'>" . $value . "</div>";
                }
            }
            
            $content = $content . "<hr>" . $footer . "<br>" . $love_thanks;
            $title = "修改后的 " . $time . " 的作业";    // 邮件主题
            $sql = "SELECT * FROM {$prefix}Subscribers";
            $result = $conn->query($sql);
            // 循环，给每一个订阅者发送邮件
            if ($result->num_rows > 0)
            {
                while ($arr = $result->fetch_assoc())
                {
                    $nickname = $arr["name"];
                    $receiver = $arr["email"];
                    $content2 = "<img src='$setup_link/hw-imgs/logo.jpg' style='height: 96px; width: 96px;'>
                    <h2>$time <span>的作业</span></h2>
                    嗨~" . $nickname . "~<br>以下是今天的作业（修改后）：" . $content;
                    // 发送作业邮件
                    send_email_class::send_email($email_json, $semail, $sname, $receiver, $nickname, $remail, $title, $content2, $content);
                }
            }
        }
    }
?>
