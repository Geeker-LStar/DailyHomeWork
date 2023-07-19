<!--作业显示界面-->

<?php
    require_once("./config.php");
?>

<?php
    // 连接数据库
    date_default_timezone_set("PRC");    // 时区
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("连接失败：" . $conn->connect_error);
?>

<?php
    $type = $_GET["type"];
    if ($type == null)
        header("Location: index.php");
    if ($type == "show")    // 显示作业 
    {
        $tablen = $prefix . $_GET["time"];
        if ($tablen == null)
            header("Location: index.php");    // 时间为空则默认跳转至首页
        $sql = "SHOW TABLES LIKE '$tablen'";    // 查询数据表是否存在（即是否有该年该月的作业）
        $result = $conn->query($sql);
        if ($result->num_rows == 0)
            header("Location: index.php");
        $year = substr($_GET["time"], 0, 4);
        $month = substr($_GET["time"], 5, 2);
    }
?>

<!DOCTYPE html>
<html id="html">
    <head>
        <meta charset="utf-8">
      
        <link rel="stylesheet" href="hw-css/background.css">
        <!--<link rel="stylesheet" href="hw-css/font.css">-->
        <link rel="stylesheet" href="hw-css/header.css">
        <link rel="stylesheet" href="hw-css/footer.css">
        <link rel="stylesheet" href="hw-css/leftcolumn.css">
        <link rel="stylesheet" href="hw-css/rightcolumn.css">
        <link rel="stylesheet" href="hw-css/images.css">
        <link rel="stylesheet" href="hw-css/responsive_layout.css">
        <link rel="stylesheet" href="hw-css/row.css">
        <link rel="stylesheet" href="hw-css/button.css">
        <script src="hw-js/darkmode-system.js"></script>
        <script src="hw-js/darkmode.js"></script>
    </head>
    
    <body>
        <script>
            // ajax 异步查找作业
            function showHw(time)
            {   
                var xmlhttp;
                if (time == "") {
                    document.getElementById(hw).innerHTML = "";
                    return;
                }
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("hw").innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "search.php?time_public="+time, true);
                xmlhttp.send();
            }
        </script>
        
        <div class="pic">
            <img src="hw-imgs/logo.jpg" style="height: 220px; width: 220px;">
        </div>
        
        <?php 
            if ($type == "show") { 
                
        ?>
        <title><?php echo $year;?> 年 <?php echo $month;?> 月的每日作业 — cfc5</title>
        <div class="header">
            <h1><?php echo $year;?> 年 <?php echo $month;?> 月</h1>
            <h3><?php echo $header_secondary_title;?></h3>
        </div>
        
        <div class="topnav"><?php echo $link_list;?></div>
        
        <div class="row">
            <div class="leftcolumn">
                <div class="left_card">
                    <h1>以下是 <?php echo $year;?> 年 <?php echo $month;?> 月的每日作业。</h1>
                    <?php
                        // 显示本年本月所有作业
                        $sql = "SELECT * FROM $tablen ORDER BY id DESC";
                        $result = $conn->query($sql);
                        $nums = $result->num_rows;
                        
                        while ($each_day = $result->fetch_assoc())
                        {
                            echo "<br><h2>" . $month . "/". $each_day["date"] . "</h2>";
                            foreach ($each_day as $key => $value)
                            {
                                if ($key != "id" && $key != "date" && $value != null && $value != "无" && $value != "<br>")
                                {
                                    echo "<h3>" . $translation[$key] . "</h3>";
                                    echo "<div style='line-height: 2'>" . $value . "</div>";
                                }
                            }
                        }
                    ?>
                </div>
            </div>  
            <?php 
                }
                if ($type == "search")
                { 
                    if (isset($_GET["date"]))
                    {
                        $date = $_GET["date"];
                        $year = substr($date, 0, 4);
                        $month = substr($date, 5, 2);
                        $day = substr($day, 8, 2);
                        $tablen = $year . "_" . $month;
                        $sql = "SELECT * FROM $tablen WHERE date={$day}";
                        $result = $conn->query($sql);
                        $hw_arr = $result->fetch_assoc();
                        echo "以下是" . $date . "的作业：";
                        foreach ($hw_arr as $key => $value)
                        {
                            if ($key != "id" && $key != "date" && $value != null && $value != "无")
                            {
                                echo "<h3>" . $translation[$key] . "</h3>";
                                echo "<div style='line-height: 2'>" . $value . "</div>";
                            }
                        }
                    }
            ?>
            <title><?php echo $year;?>来查查那天的作业吧~ — cfc5</title>
            <div class="header">
                <h1>查找作业</h1>
                <h3><?php echo $header_secondary_title;?></h3>
            </div>
            
            <div class="topnav"><?php echo $link_list;?></div>
            
            <div class="row">
                <div class="leftcolumn">
                    <div class="left_card">
                        <h1>来查查那天的作业吧~</h1>
                        <p>请选择要查找的日期：</p>
                        <p>（如要查找周末作业，请选择周五的时间。）</p>
                        <form action="">
                            <input type="date" onchange="showHw(this.value)">
                        </form>
                        <div id="hw"></div>
                    </div>
                </div>  
            <?php
                }
            ?>
            <div class="rightcolumn">
                <div class="right_card", style="height: 240px;">
                    <h2><?php echo $right_1;?></h2>
                    <div class="img">
                        <img src="hw-imgs/logo.jpg" style="height: 120px; width: 120px;">
                    </div>
                    <div style="line-height: 1.5"><p><?php echo $class_introd;?></p></div>
                </div>
                <div class="right_card", style="height: 150px;">
                    <h2><?php echo $right_2;?></h2>
                    <p><?php echo $contact_1;?></p>
                    <p><?php echo $contact_2;?></p>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <h2><?php echo $footer;?></h2>
            <p><?php echo $love_thanks;?></p>
        </div>
        
        <img id="switch" src="hw-imgs/light_pic.png" onclick="dark_mode()" style="position: fixed; bottom: 20px; right: 20px; height: 60px; width: 60px;">
        
    </body>
</html>
