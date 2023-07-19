<!--作业发布网站（安装）-->

<!DOCTYPE html>
<?php
    require_once("config.php");
    require_once("functions.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <title>安装 —— 每日作业发布</title>
    </head>
    
    <body>
        <div class="flex h-screen items-center justify-center p-6">
            <div class="m-auto">
                <div class="mb-6">
                    <img src="hw-imgs/dhw_logo.jpg" style="height: 150px;" class="m-auto mt-6">
                </div>
            <?php
                if ($_POST["step"] != "finished") {
            ?>
                <h1 class="text-4xl font-semibold mb-6">安装 DHW 每日作业至您的网站</h1>
                <h3 class="text-xl font-semibold mb-6 text-right">——极简的三步安装程序</h3>
            <?php
                }
            ?>
                
        <!--第一步：配置数据库-->
        <?php
            if (!isset($_POST["step"])) {
        ?>
                <h3 class="text-xl font-semibold mb-6">第一步：配置数据库</h3>
                
                <form action="setup.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="server">
                            数据库主机
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="server" value="localhost">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            数据库用户名
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="username">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            数据库密码
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="password" name="password">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="dbname">
                            数据库名称
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="dbname">
                        </div>
                    </div>
                     <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="prefix">
                            数据表前缀
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="prefix" value="dhw_">
                        </div>
                    </div>
                    <input type="hidden" name="step" value="2">
                    <div class="flex items-center justify-between">
                        <input type="submit" name="connect" value="配置数据库" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    </div>
                </form>
                <p class="text-center text-gray-500 text-base mb-6">
                    &copy;2023~<?php echo date("Y");?> DHW 每日作业保留所有权利。
                </p>
            </div>
        </div>
        
        <?php
            }
        ?>
        
        <?php
            // 获取数据库配置信息
            if (isset($_POST["connect"]))
            {
                // 获取数据
                $servername = $_POST["server"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                $dbname = $_POST["dbname"];
                $prefix = $_POST["prefix"];
                $begin_year = date("Y");
                $begin_time = date("Y-m-d H:i:s");
                
                // 修改配置文件
                modify_configs("servername = ", $servername);
                modify_configs("username = ", $username);
                modify_configs("password = ", $password);
                modify_configs("dbname = ", $dbname);
                modify_configs("prefix = ", $prefix);
                modify_configs("begin_year = ", $begin_year);
                modify_configs("begin_time = ", $begin_time);
            }
        ?>
        
        <?php
            // 连接数据库
            date_default_timezone_set("PRC");    // 时区
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error)
                die("连接失败：" . $conn->connect_error);
        ?>
        
        <?php
            // 创建存放配置信息的数据表
            $sql = "CREATE TABLE `{$dbname}` . `{$prefix}Configs` ( 
                    `id` INT(20) NOT NULL AUTO_INCREMENT COMMENT '自增主键' , 
                    `cfgkey` VARCHAR(256) NOT NULL COMMENT '配置项目（键）' , 
                    `cfgvalue` VARCHAR(1320) NOT NULL COMMENT '配置项目（值）' , 
                    `description` VARCHAR(725) NOT NULL COMMENT '配置项目（描述）' , 
                    PRIMARY key (`id`)) ENGINE = InnoDB COMMENT = '网站配置信息';";
            $conn->query($sql);
            // 创建存放用户（管理员）信息的数据表
            $sql = "CREATE TABLE `{$dbname}`. `{$prefix}Users` ( 
                    `id` INT(20) NOT NULL AUTO_INCREMENT COMMENT '自增主键' , 
                    `username` VARCHAR(20) NOT NULL COMMENT '管理员用户名' , 
                    `password` VARCHAR(24) NOT NULL COMMENT '管理员密码' , 
                    `extra` VARCHAR(725) NOT NULL COMMENT '管理员额外信息' , 
                    PRIMARY key (`id`)) ENGINE = InnoDB COMMENT = '网站管理员的信息';";
            $conn->query($sql);
            // 创建存放订阅者信息的数据表
            $sql = "CREATE TABLE `{$dbname}`. `{$prefix}Subscribers` ( 
                    `id` INT(20) NOT NULL AUTO_INCREMENT COMMENT '自增主键' , 
                    `name` VARCHAR(20) NOT NULL COMMENT '订阅者用户名' , 
                    `email` VARCHAR(256) NOT NULL COMMENT '订阅者邮箱' , 
                    `is_receive` INT(1) NOT NULL COMMENT '订阅者是否接收邮件（默认接收）' , 
                    `time` VARCHAR(30) NOT NULL COMMENT '订阅时间' , 
                    PRIMARY key (`id`)) ENGINE = InnoDB COMMENT = '网站管理员的信息';";
            $conn->query($sql);
        ?>
        
        <!--第二步：设置管理员账户-->
        <?php
            if (isset($_POST["step"]) && $_POST["step"] == 2) {
        ?>
                <h3 class="text-xl font-semibold mb-6">第二步：设置管理员账户</h3>
                
                <form action="setup.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <p class="mb-4 text-red-500">请记住您的用户名和密码。</p>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_usern">
                            管理员用户名
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="admin_usern">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_pwd">
                            管理员密码
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="password" name="admin_pwd">
                        </div>
                    </div>
                    <input type="hidden" name="step" value="3">
                    <div class="flex items-center justify-between">
                        <input type="submit" name="admin" value="设置账户" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    </div>
                </form>
                <p class="text-center text-gray-500 text-base">
                    &copy;2023~<?php echo date("Y");?> DHW 每日作业保留所有权利。
                </p>
            </div>
        </div>
        <?php
            }
        ?>
        
        <?php
        if (isset($_POST["admin"]))
        {
            $a_u = $_POST["admin_usern"];
            $a_p = $_POST["admin_pwd"];
            $sql = "INSERT INTO {$prefix}Users (username, password) VALUES ('{$a_u}', '{$a_p}')";
            $conn->query($sql);
            echo mysqli_error($conn);
        }
        ?>
        
        <!--第三步：设置网站标题等重要内容-->
        <?php
            if (isset($_POST["step"]) && $_POST["step"] == 3) {
        ?>
                <h3 class="text-xl font-semibold mb-6">第三步：设置网站基本信息</h3>
                
                <form action="setup.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="site_title">
                            浏览器标签页标题
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="site_title">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="header_main_title">
                            网站主标题
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="header_main_title">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="header_secondary_title">
                            网站副标题
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="header_secondary_title">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="class_full_name">
                            班级全称
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="class_full_name">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="class_short_name">
                            班级简称
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" type="text" name="class_short_name">
                        </div>
                    </div>
                    <input type="hidden" name="step" value="finished">
                    <div class="flex items-center justify-between">
                        <input type="submit" name="basic_info" value="确认安装！" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    </div>
                </form>
                <p class="text-center text-gray-500 text-base">
                    &copy;2023~<?php echo date("Y");?> DHW 每日作业保留所有权利。
                </p>
            </div>
        </div>
        <?php
            }
        ?>
        
        <?php
            if (isset($_POST["basic_info"]))
            {
                // 获取数据
                $site_title = $_POST["site_title"];
                $header_main_title = $_POST["header_main_title"];
                $header_secondary_title = $_POST["header_secondary_title"];
                $class_short_name = $_POST["class_short_name"];
                $class_full_name = $_POST["class_full_name"];
                
                // 修改配置文件
                modify_configs("site_title = ", $site_title);
                modify_configs("header_main_title = ", $header_main_title);
                modify_configs("header_secondary_title = ", $header_main_title);
                modify_configs("class_short_name = ", $class_short_name);
                modify_configs("class_full_name = ", $class_full_name);
            }
        ?>
        
        <!--安装成功-->
        <?php
            if (isset($_POST["step"]) && $_POST["step"] == "finished") {
        ?>
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h3 class="text-xl font-semibold mb-4">安装成功！</h3>
                <p class="mb-4">登录名：您设置的管理员用户名</p>
                <p class="mb-4">登录密码：您设置的管理员密码</p>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="window.location.href='backstage.php';">去后台</button>
            </div>
            <p class="text-center text-gray-500 text-base">
                &copy;2023~<?php echo date("Y");?> DHW 每日作业保留所有权利。
            </p>
        <?php
            }
        ?>
    </body>
</html>
