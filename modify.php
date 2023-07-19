<!--作业发布后台（修改）-->

<?php
    session_start();
?>
<?php
    // 检查是否登录，未登录则要求登录
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>修改 / 查找作业 - 作业发布后台</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
    </head>
    
    <body>
        <script>
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
                xmlhttp.open("GET", "search.php?time="+time, true);
                xmlhttp.send();
            }
        </script>
        
        <h1 class="text-4xl font-semibold px-8 py-6">修改 / 查找作业</h1>
        <p><strong class="text-red-500 mx-8 pb-6 block">注：给订阅者发送邮件可能耗时较长，点击修改后不会立即提示邮件发送成功，此时可关闭该页面或打开其它页面（如首页），不影响程序的运行。</strong></p>
        <p><strong class="px-8 py-4 font-semibold">请选择要修改 / 查找的作业的日期</strong></p>
        <p class="px-8 py-4">如要查找周末作业，请选择周五的时间。</p>
    
        <form action="">
            <input type="date" onchange="showHw(this.value)" class="bg-green-300 hover:bg-green-500 text-white font-bold py-2 mx-8 px-6 rounded focus:outline-none focus:shadow-outline">
        </form>
        <div id="hw"></div>
    </body>
</html>