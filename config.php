<!--全局配置-->

<?php
    // 版本
    $version = '1.2.0';
    // 数据库配置（不可通过 mdf_cfg.php 修改）
    $servername = '';    // 服务器
    $username = '';    // 数据库用户名
    $password = '';    // 数据库密码
    $dbname = '';    // 数据库名称
    $prefix = '';    // 数据表前缀
    
    // 班级基本信息
    $class_full_name = '';    // 班级全称
    $class_short_name = '';    // 班级简称
    $class_introd = '';    // 班级简介
    $class_motto = '';    // 班训
    $class_slogan = '';    // 口号
    
    // 网站时间
    $begin_year = '';    // 开始年份
    $all_years = $begin_year . "~" . date("Y");    // 全部年份
    $begin_time = '';    // 开始的详细时间
    
    // 网站标题等配置
    $site_title = '';    // 网站标题（浏览器标签显示）
    $header_main_title = '';    // 网站首页主标题
    $header_secondary_title = '';    // 网站主页副标题
    $footer = "©" . $all_years . " " . $class_short_name . " 全体同学保留所有权利";    //（不可通过 mdf_cfg.php 修改）
    $love_thanks = '';
    
    // 邮件配置
    $semail = '';    // 默认发件人邮箱
    $sname = '';    // 默认发件人姓名
    $remail = '';    // 默认回复至的邮箱
    $email_json = '{"Host":"","Username":"","Password":"","SMTPSecure":"","Port":""}';    // smtp 服务
    
    // 将数据表的字段名翻译成中文以显示
    $translation = array("Chinese"=>"语文", "Math"=>"数学", "English"=>"英语", "Physics"=>"物理", "Chemistry"=>"化学", "Biology"=>"生物", "Geography"=>"地理", "History"=>"历史", "Politics"=>"道法", "More"=>"其他");
    
    // 联系方式
    $contact_1 = '';
    $contact_2 = '';
    
    // 教师寄语
    $teachers_hope = '';
                
    // 右侧栏三个 card 的标题
    $right_1 = "关于我们";
    $right_2 = "教师寄语";
    $right_3 = "联系我们";
    
    // 链接列表
    $link_list = "<a href='index.php'>首页</a>
                <a href='subscribe.php'>订阅</a>
                <a href='hw.php?type=search'>查找作业</a>
                <a href='https://dailyhw.ltx1102.com/'>DHW 每日作业 —— 官网</a>";
?>

<?php
    if ($username == '' && substr($_SERVER["REQUEST_URI"], -10) != "/setup.php")
        header("Location: setup.php");
?>

<?php
    if (file_exists("version.update") && substr($_SERVER["REQUEST_URI"], -15) != "/isupdating.php")
        header("Location: isupdating.php");
?>

<?php
    $link =  'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $arr = explode("/", $link);
    $setup_link = $arr[0];
    for ($i = 1; $i < count($arr)-1; $i++)
        $setup_link = $setup_link . "/" . $arr[$i];    // 安装目录
?>