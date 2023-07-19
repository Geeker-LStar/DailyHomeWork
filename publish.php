<!--作业发布后台（发布）-->

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
        <title>发布作业 - 作业发布后台</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
    </head>
    
    <body>
        <h1 class="text-4xl font-semibold px-8 py-6">发布作业</h1>
        <strong class="text-red-500 mx-8">注：给订阅者发送邮件可能耗时较长，点击发布后不会立即提示邮件发送成功，此时可关闭该页面或打开其它页面（如首页），不影响程序的运行。</strong>
        <?php
            // 获取年月（数据表名）日（日期）
            $table_name = date("Y_m");
            $date = date("d");
        ?>
        <form action="execute.php" method="post" class="bg-white shadow-md rounded px-8 pt-4 pb-8 mb-4">
            <input type="hidden" name="type" value="publish">
            <label class="block text-gray-700 text-base font-bold my-2" for="tablen">
            数据表名（格式为 YYYY_MM，请按格式填写）
            </label>
            <input id="tablen" name="tablen" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $table_name;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="date">
            日期
            </label>
            <input id="date" name="date" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $date;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="yw">
            语文
            </label>
            <textarea id="yw" name="yw" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="sx">
            数学
            </label>
            <textarea id="sx" name="sx" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="yy">
            英语
            </label>
             <textarea id="yy" name="yy" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="wl">
            物理
            </label>
            <textarea id="wl" name="wl" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="hx">
            化学
            </label>
            <textarea id="hx" name="hx" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="sw">
            生物
            </label>
            <textarea id="sw" name="sw" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="dl">
            地理
            </label>
            <textarea id="dl" name="dl" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="ls">
            历史
            </label>
            <textarea id="ls" name="ls" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="df">
            道法
            </label>
            <textarea id="df" name="df" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <label class="block text-gray-700 text-base font-bold my-2" for="qt">
            其他
            </label>
            <textarea id="qt" name="qt" style="height: 180px; width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
            <div class="flex items-center justify-between">
                <input type="submit" name="publish" value="确认发布" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </body>
</html>

