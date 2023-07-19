<!--作业发布后台（集成）-->

<?php
    session_start();
?>

<?php
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<?php
    $magic_num = rand(100000, 999999);    // 随机数，保证每次显示的图片都是最新的（无论是否开启缓存）
?>

<?php
    require_once("config.php");
    require_once("functions.php");
?>

<?php
    if (isset($_GET["logout"]))
        log_out();
?>
<!DOCTYPE html>
<html id="html">
    <head>
        <title>作业发布后台</title>
        <link rel="stylesheet" href="hw-css/full.css">
        <link rel="stylesheet" href="hw-css/backstage.css">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <link rel="stylesheet" href="hw-includes/font-awesome-4.7.0/css/font-awesome.css">
    </head>
    
    <body>
        <script>
            function iframe(php_link)
            {   
                var link = php_link + ".php"
                ifr = document.getElementById("ifr");
                ifr.src = link;
            }
            function show_form() {
                var display = document.getElementById("form").style.display;
                if (display == "none") {
                    document.getElementById("form").style.display = "block";
                }
                if (display == "block") {
                    document.getElementById("form").style.display = "none";
                }
            }
        </script>
        <div class="left bg-gray-700 text-white">
            <div class="px-8 py-8 bg-gray-900">
                <h2 class="text-2xl font-semibond"><a href="https://dailyhw.ltx1102.com">DHW 每日作业</a></h2>
            </div><hr>
            <div class="px-8 py-6 bg-gray-900">
                <img src="hw-imgs/logo.jpg?magic_num=<?php echo $magic_num;?>" class="inline-block rounded-full mr-2" style="height: 50px; width: 50px;">
                <h3 class="inline-block text-xl font-semibond"><?php echo $_SESSION["user"];?></h3>
                <form action="backstage.php" method="get" class="mt-4 inline-block float-right"><input type="submit" name="logout" value="登出" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"></form>
            </div><hr>
            <a onclick="iframe('backstage_greet');">
                <div class="px-8 py-6">
                <i class="fa fa-location-arrow mr-1"></i>
                欢迎页面
            </div></a><hr>
            <a onclick="iframe('index');">
                <div class="px-8 py-6">
                <i class="fa fa-fw fa-home mr-1"></i>
                首页
            </div></a><hr>
            <a onclick="iframe('publish')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-tags mr-1"></i>
                发布作业
            </div></a><hr>
            <a onclick="iframe('modify')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-search mr-1"></i>
                修改 / 查找作业
            </div></a><hr>
            <a onclick="iframe('email_service')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-envelope mr-1"></i>
                配置邮件服务
            </div></a><hr>
            <a onclick="iframe('mng_sscrb')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-users mr-1"></i>
                管理订阅者
            </div></a><hr>
            <a onclick="iframe('set_imgs')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-file-picture-o mr-1"></i>
                设置特色图片
            </div></a><hr>
            <a onclick="iframe('mdf_cfg')"><div class="px-8 py-6">
                <i class="fa fa-fw fa-refresh mr-1"></i>
                修改配置
            </div></a><hr>
        </div>
        <?php
            for ($i = 0; $i < count($ar = explode(".", $version)); $i++)
                $now_version = $now_version . $ar[$i];
        ?>
        <?php
            $isnew = 1;
            $url = "https://dailyhw.ltx1102.com/versions.txt";
            $ch = curl_init($url);    
            $dir = './';    // 初始化保存文件的目录名
            $file_name = basename($url);    // 函数返回文件名
            $save_file_loc = $dir . $file_name;    // 将文件保存到指定位置
            $fp = fopen($save_file_loc, 'wb');    // 打开文件
            // 为 curl 传输设置一个选项
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_exec($ch);    // 执行 curl 对话
            curl_close($ch);    // 关闭会话并释放所有资源
            fclose($fp);    // 关闭文件
            $handle = fopen($file_name, "r");    // 读模式打开文件
            $contents = fread($handle, filesize($file_name));    // 获取文件内容
            fclose($handle);
        ?>
        <form method="post" action="update_version.php" style="position: fixed; top: 20; right: 30">
            <center><select name="version" class="border rounded ml-4">
            <?php
                $version_ar = explode("/", $contents);
                echo "<option value='0.0.0'>版本升级</option>";
                for ($i = 0; $i < count($version_ar); $i++)
                {
                    $upd_version = "";
                    for ($j = 0; $j < count($ar = explode(".", $version_ar[$i])); $j++) {
                        $upd_version = $upd_version . $ar[$j];
                    }
                    if (($upd_version - $now_version) > 0)
                    {
                        echo "<option value='$version_ar[$i]'>$version_ar[$i]</option>";
                        $isnew = 0;
                    }
                }
                if ($isnew)
                    echo "<option value='0.0.0'>已是最新版。</option>";
            ?>
            </select>
            <input type="submit" name="update" value="确定" class="block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-2 rounded focus:outline-none focus:shadow-outline"></center>
        </form>
        
        <div id="modal_div"></div>
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="form" style="display: none;">
            <div class="relative top-20 mx-auto p-5 border shadow-lg rounded-md bg-white" style="width: 30rem;">
                <button class="h-8 w-16 bg-red-500 hover:bg-red-700 focus:shadow-outline focus:outline-none text-white font-bold py-1 px-4 rounded float-right" onclick="show_form();">取消</button>
                <div class="mt-3 text-center">
                    <img src="hw-imgs/logo.jpg" style="height: 100px; width: 100px" class="m-auto mt-4 mb-4">
    	            <h2 class="text-2xl font-semibold text-gray-900 mb-3">使用体验反馈</h2>
            		<form class="w-full max-w-sm" method="post" action="feedback.php" id="feedback_form">
            		    <p class="mb-4 mt-1 pl-10">填写此表单后，网页将被刷新。所以如果您正在填写其他表单，请填写完毕后再来填写此表单，否则其他表单中的数据将会丢失。</p>
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="name">
                                    昵称
                                </label>
                            </div>
                            <div class="md:w-2/3">
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="name" type="text" name="name" value=<?php echo $_SESSION["user"];?>>
                            </div>
                        </div>
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="contact">
                                    联系方式
                                </label>
                            </div>
                            <div class="md:w-2/3">
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="contact" type="text" name="contact">
                            </div>
                        </div>
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="feedback">
                                    反馈内容
                                </label>
                            </div>
                            <div class="md:w-2/3">
                            <textarea class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="feedback" type="text" name="feedback" style="height: 15rem"></textarea>
                            </div>
                        </div>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3"></div>
                            <div class="md:w-2/3">
                                <input class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit" name="submit" value="提交" onclick="feedback();">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <img src="hw-imgs/dhw_feedback.jpg" style="height: 40px; weight: 40px; position: fixed; bottom: 20px; right: 30px;" onclick="show_form();">
        <div class="right">
            <iframe height="100%" width="100%" src="backstage_greet.php" id="ifr"></iframe>
        </div>
    </body>
</html>