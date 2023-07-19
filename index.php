<!--作业发布网站首页-->

<?php
    require_once("config.php");
?>

<?php
    // 连接数据库
    date_default_timezone_set("PRC");    // 时区
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("连接失败：" . $conn->connect_error);
?>

<?php
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);
    $table_ar = array();
    while ($each_tb_name = $result->fetch_assoc()["Tables_in_{$dbname}"])
    {
        $len = strlen($prefix);
        $each_tb_name = substr($each_tb_name, $len);
        if (preg_match("/^(\d){4}_(\d){2}$/", $each_tb_name))
            array_push($table_ar, $each_tb_name);
    }
?>

<?php
    $magic_num = rand(100000, 999999);    // 随机数，保证每次显示的图片都是最新的（无论是否开启缓存）
?>
        
<!DOCTYPE html>
<html id="html">
    <head>
        <title><?php echo $site_title;?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="hw-css/background.css">
        <!--<link rel="stylesheet" href="hw-css/font.css">-->
        <link rel="stylesheet" href="hw-css/header.css">
        <link rel="stylesheet" href="hw-css/footer.css">
        <link rel="stylesheet" href="hw-css/leftcolumn.css?id=1">
        <link rel="stylesheet" href="hw-css/rightcolumn.css">
        <link rel="stylesheet" href="hw-css/images.css">
        <link rel="stylesheet" href="hw-css/responsive_layout.css">
        <link rel="stylesheet" href="hw-css/row.css">
        <link rel="stylesheet" href="hw-css/button.css">
        <script src="hw-js/darkmode-system.js"></script>
        <script src="hw-js/darkmode.js"></script>
    </head>
    
    <body>
        <div class="pic">
            <img src="hw-imgs/logo.jpg?random=<?php echo $magic_num;?>" style="height: 220px; width: 220px;">
        </div>
        
        <div class="header">
            <h1><?php echo $header_main_title;?></h1>
            <h3><?php echo $header_secondary_title;?></h3>
        </div>
        
        <div class="topnav"><?php echo $link_list;?></div>
        
        <div class="row">
            <div class="leftcolumn">
                <div class='left_card' style='height: 210px;'>
                    <div class='img'>
                        <img src='hw-imgs/search.jpg' style='height: 170px; width: 250px;'>
                    </div>
                    <a href='hw.php?type=search'><h2>查找作业</h2></a>
                    <p>来查查那天的作业吧~</p>
                    <div class='btn' style='vertical-align: middle;'>
                        <a href='hw.php?type=search' style='color: black;'>阅读更多</a>
                    </div>
                </div>
                <?php
                    for ($i = count($table_ar)-1; $i >= 0; $i--)
                    {
                        $year = substr($table_ar[$i], 0, 4);
                        $month = substr($table_ar[$i], 5, 2);
                        echo "<div class='left_card' style='height: 210px;'>
                            <div class='img'>
                                <img src='hw-imgs/$table_ar[$i].jpg' style='height: 170px; width: 250px;'>
                            </div>
                            <a href='hw.php?type=show&time=$table_ar[$i]'><h2>$year 年 $month 月</h2></a>
                            <p>$year 年 $month 月的每日作业~</p>
                            <div class='btn' style='vertical-align: middle;'>
                                <a href='hw.php?type=show&time=$table_ar[$i]' style='color: black;'>阅读更多</a>
                            </div>
                          </div>";
                    }
                ?>
            </div>
            
            <div class="rightcolumn">
                <div class="right_card", style="height: 270px;">
                    <h2><?php echo $right_1;?></h2>
                    <div class="img">
                        <img src="hw-imgs/logo.jpg?random=<?php echo $magic_num;?>" style="height: 120px; width: 120px;">
                    </div>
                    <div style="line-height: 1.5"><p><?php echo $class_introd;?></p></div>
                </div>
                <div class="right_card", style="height: 150px;">
                    <h2><?php echo $right_3;?></h2>
                    <p>联系方式一：<?php echo $contact_1;?></p>
                    <p>联系方式二：<?php echo $contact_2;?></p>
                </div>
                <div class="right_card", style="height: 300px;">
                    <h2><?php echo $right_2;?></h2>
                    <p><?php echo $teachers_hope;?></p>
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
