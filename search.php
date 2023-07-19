<!--作业发布后台（查询）-->

<?php
    session_start();
?>

<?php
    require_once("config.php");
    require_once("functions.php");
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
        <title>查询 - 作业发布后台</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <body>
        <?php
            // 说明是后台发起的查询
            if (isset($_GET["time"])) {
        ?>
            <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <?php
                $time = $_GET["time"];
                $year = substr($time, 0, 4);
                $month = substr($time, 5, 2);
                $day = substr($time, 8, 2);
                $tablen = $prefix . $year . "_" . $month;
                $sql = "SHOW TABLES LIKE '{$tablen}'";
                $result = $conn->query($sql);
                $exist = $result->num_rows;
                // 不存在则报错
                if ($exist == 0)
                    echo "<br>数据表 " . $tablen . " 不存在。<br>";
                $sql = "SELECT * FROM {$tablen} WHERE date={$day}";
                $result = $conn->query($sql);
                $hw_arr = $result->fetch_assoc();
                $len = strlen($prefix);
                $tablen = substr($tablen, $len);
                $date = $hw_arr["date"];
                $yw = $hw_arr["Chinese"];
                $sx = $hw_arr["Math"];
                $yy = $hw_arr["English"];
                $wl = $hw_arr["Physics"];
                $hx = $hw_arr["Chemistry"];
                $sw = $hw_arr["Biology"];
                $dl = $hw_arr["Geography"];
                $ls = $hw_arr["History"];
                $df = $hw_arr["Politics"];
                $qt = $hw_arr["More"];
                
                $yw = delete_br_break($yw);
                $sx = delete_br_break($sx);
                $yy = delete_br_break($yy);
                $wl = delete_br_break($wl);
                $hx = delete_br_break($hx);
                $sw = delete_br_break($sw);
                $dl = delete_br_break($dl);
                $ls = delete_br_break($ls);
                $df = delete_br_break($df);
                $qt = delete_br_break($qt);

                if ($yw == null && $sx == $null && $yy == null)
                    echo "<br><p class='px-8 '>未查找到 " . $tablen . "_" . $day . " 的作业。</p>";
                else {
        ?>
        <h2 class="text-2xl font-semibold px-8 pt-6">以下是<?php echo $tablen;?>_<?php echo $day;?> 的作业</h2>
        
        <form action="execute.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="type" value="modify">
            <label class="block text-gray-700 text-base font-bold my-2" for="tablen">
            数据表名（格式为 YYYY_MM，请按格式填写）
            </label>
            <input id="tablen" name="tablen" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $tablen;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="date">
            日期
            </label>
            <input id="date" name="date" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $date;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="yw">
            语文
            </label>
            <textarea id="yw" name="yw" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $yw;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="sx">
            数学
            </label>
            <textarea id="sx" name="sx" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $sx;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="yy">
            英语
            </label>
             <textarea id="yy" name="yy" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $yy;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="wl">
            物理
            </label>
            <textarea id="wl" name="wl" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $wl;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="hx">
            化学
            </label>
            <textarea id="hx" name="hx" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $hx;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="sw">
            生物
            </label>
            <textarea id="sw" name="sw" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $sw;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="dl">
            地理
            </label>
            <textarea id="dl" name="dl" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $dl;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="ls">
            历史
            </label>
            <textarea id="ls" name="ls" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $ls;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="df">
            道法
            </label>
            <textarea id="df" name="df" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $df;?></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="qt">
            其他
            </label>
            <textarea id="qt" name="qt" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"><?php echo $qt;?></textarea>
            <div class="flex items-center justify-between">
                <input type="submit" name="modify" value="确认修改" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="window.location.href='modify.php'">
            </div>
        </form>
        <?php
                };
            };
        ?>
        
        <?php
            // 说明是用户（在公有页面）发起的查询
            if (isset($_GET["time_public"]))
            {
                $time = $_GET["time_public"];
                $year = substr($time, 0, 4);
                $month = substr($time, 5, 2);
                $day = substr($time, 8, 2);
                $tablen = $prefix . $year . "_" . $month;
                $sql = "SELECT * FROM {$tablen} WHERE date={$day}";
                $result = $conn->query($sql);
                if ($result->num_rows == 0)
                    echo "<br>未查找到 " . $tablen . "_" . $day . " 的作业。";
                else
                {   
                    $hw_arr = $result->fetch_assoc(); 
                    echo "<br><h2>以下是" . $time . "的作业：</h2>";
                    foreach ($hw_arr as $key => $value)
                    {
                        if ($key != "id" && $key != "date" && $value != null && $value != "无")
                        {
                            echo "<h3>" . $translation[$key] . "</h3>";
                            echo "<div style='line-height: 2'>" . $value . "</div>";
                        }
                    }
                }
            }
        ?>
    </body>
</html>