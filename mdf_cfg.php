<!--作业发布后台（修改配置）-->

<?php
    require_once("config.php");
    require_once("functions.php");
?>

<?php
    session_start();
    $usern = $_SESSION["user"];
?>

<?php
    // 连接数据库
    date_default_timezone_set("PRC");    // 时区
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("连接失败：" . $conn->connect_error);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <title>修改配置 —— 作业发布后台</title>
    </head>
    
    <body>
        <!--修改管理员账户——模态框-->
        <!--修改网站信息——模态框-->
        <div id="modal_div"></div>
        <h1 class="text-4xl font-semibold px-8 py-6">修改配置</h1>
        <strong class="px-8 py-4 font-semibold">全面地填写网站内容信息，有利于站点 SEO 评分。</strong>
        <p class="px-8 py-4">注 1：除班级简介和教师寄语外，网站内容信息不支持特殊符号。</p>
        <p class="px-8 pb-4">注 2：管理员用户名应由英文、数字和下划线组成；密码应由英文大小写、数字和特殊符号组成，且长度不小于 8 位。</p>
        <hr>
        <h3 class="px-8 py-4 text-xl font-semibold">修改管理员账户信息</h3>
        <?php
            $sql = "SELECT * FROM {$prefix}Users WHERE username='{$usern}'";
            $result = $conn->query($sql);
            $passwd = $result->fetch_assoc()["password"];
        ?>
        <form action="mdf_cfg.php" method="post" class="bg-white shadow-md rounded px-8 pb-8 mb-4">
            <input type="hidden" name="old_usern" value=<?php echo $usern;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="usern">
                用户名
            </label>
            <input type="text" name="usern" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $usern;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="password">
                密码
            </label>
            <input type="password" name="password" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $passwd;?>>
            <div class="flex items-center justify-between">
                <input type="submit" name="mdf_admin" value="确认修改" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">
            </div>
        </form>
        <hr>
        <h3 class="px-8 py-4 text-xl font-semibold">修改网站内容信息</h3>
        <form action="mdf_cfg.php" method="post" class="bg-white shadow-md rounded px-8 pb-8 mb-4">
            <!--网站标题等配置-->
            <label class="block text-gray-700 text-base font-bold my-2" for="site_title">
                浏览器标签页标题
            </label>
            <input type="text" name="site_title" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $site_title;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="header_main_title">
                首页主标题
            </label>
            <input type="text" name="header_main_title" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $header_main_title;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="header_secondary_title">
                首页副标题
            </label>
            <input type="text" name="header_secondary_title" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $header_secondary_title;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="love_thanks">
                页脚致谢
            </label>
            <input type="text" name="love_thanks" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $love_thanks;?>>
            <!--班级基本信息-->
            <label class="block text-gray-700 text-base font-bold my-2" for="class_full_name">
                班级全称
            </label>
            <input type="text" name="class_full_name" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $class_full_name;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="class_short_name">
                班级简称
            </label>
            <input type="text" name="class_short_name" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $class_short_name;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="class_introd">
                班级简介
            </label>
            <textarea name="class_introd" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" style="height: 150px; width: 200px;"><?php echo delete_br_break($class_introd);?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="class_motto">
                班训
            </label>
            <input type="text" name="class_motto" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $class_motto;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="class_slogan">
                班级口号
            </label>
            <input type="text" name="class_slogan" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $class_slogan;?>>
            <!--网站时间-->
            <label class="block text-gray-700 text-base font-bold my-2" for="begin_year">
                开始的年份
            </label>
            <input type="text" name="begin_year" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $begin_year;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="begin_time">
                开始的具体时间
            </label>
            <input type="text" name="begin_time" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $begin_time;?>>
            <!--联系方式-->
            <label class="block text-gray-700 text-base font-bold my-2" for="contact_1">
                联系方式一
            </label>
            <input type="text" name="contact_1" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $contact_1;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="contact_2">
                联系方式二
            </label>
            <input type="text" name="contact_2" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $contact_2;?>>
            <!--教师寄语-->
            <label class="block text-gray-700 text-base font-bold my-2" for="teachers_hope">
                教师寄语
            </label>
            <textarea name="teachers_hope" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" style="height: 200px; width: 300px;"><?php echo delete_br_break($teachers_hope);?></textarea>
            <div class="flex items-center justify-between">
                <input type="submit" name="mdf_cfg" value="确认修改" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">
            </div>
        </form>
    </body>
</html>

<?php
    if (isset($_POST["mdf_cfg"]))
    {
        // 网站标题等配置
        $site_title = $_POST["site_title"];
        $header_main_title = $_POST["header_main_title"];
        $header_secondary_title = $_POST["header_secondary_title"];
        $love_thanks = $_POST["love_thanks"];
        // 班级基本信息
        $class_full_name = $_POST["class_full_name"];
        $class_short_name = $_POST["class_short_name"];
        $class_introd = $_POST["class_introd"];
        $class_motto = $_POST["class_motto"];
        $class_slogan = $_POST["class_slogan"];
        // 网站时间
        $begin_year = $_POST["begin_year"];
        $begin_time = $_POST["begin_time"];
        // 邮件配置
        // 联系方式
        $contact_1 = $_POST["contact_1"];
        $contact_2 = $_POST["contact_2"];
        // 教师寄语
        $teachers_hope = $_POST["teachers_hope"];
        
        modify_configs("site_title = ", $site_title);
        modify_configs("header_main_title = ", $header_main_title);
        modify_configs("header_secondary_title = ", $header_secondary_title);
        modify_configs("love_thanks = ", $love_thanks);
        
        modify_configs("class_full_name = ", $class_full_name);
        modify_configs("class_short_name = ", $class_short_name);
        $class_introd = textarea_line_break($class_introd);
        modify_configs("class_introd = ", $class_introd);
        modify_configs("class_motto = ", $class_motto);
        modify_configs("class_slogan = ", $class_slogan);
        
        modify_configs("begin_year = ", $begin_year);
        modify_configs("begin_time = ", $begin_time);
        
        modify_configs("contact_1 = ", $contact_1);
        modify_configs("contact_2 = ", $contact_2);
        
        $teachers_hope = textarea_line_break($teachers_hope);
        modify_configs("teachers_hope = ", $teachers_hope);
        
        echo "<script>showModal('success', '网站内容信息修改成功！', 'mdf_cfg.php')</script>";
    }
    
    if (isset($_POST["mdf_admin"]))
    {
        $old_usern = $_POST["old_usern"];
        $new_usern = $_POST["usern"];
        $_SESSION["user"] = $new_usern;
        $new_pwd = $_POST["password"];
        $sql = "UPDATE {$prefix}Users SET username='{$new_usern}', password='{$new_pwd}' WHERE username='{$old_usern}'";
        $conn->query($sql);
        if (mysqli_error($conn) != "")
        {
            $error = mysqli_error($conn);
            echo "<script>showModal('fail', '数据库错误！（若你没有手动修改代码或数据库结构但此错误频繁出现，请联系 2471963891@qq.com）', 'mdf_cfg.php')</script>";
        }
            
        else 
            echo "<script>showModal('success', '管理员账户信息修改成功！', 'mdf_cfg.php')</script>";
    }
?>