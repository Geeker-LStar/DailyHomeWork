<!--DHW 每日作业（更新时显示）-->

<?php
    require_once("config.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>网站升级中！</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
    </head>
    
    <body>
        <div class="flex h-screen items-center justify-center p-6">
            <div class="m-auto">
                <div class="mb-6">
                    <img src="hw-imgs/logo.jpg" style="height: 150px;" class="m-auto mt-6">
                </div>
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h3 class="text-xl font-semibold mb-4">网站升级中！</h3>
                    <p class="mb-4">亲爱的来访者：</p>
                    <p class="mb-4">本网站正在升级，预计耗时 3-5min，稍后再来吧~</p>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="window.location.href='https://dailyhw.ltx1102.com/';">去官网逛逛</button>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="window.location.href='index.php';">刷新</button>
                </div>
                <p class="text-center text-gray-500 text-base">
                    &copy;2023~<?php echo date("Y");?> <?php echo $class_short_name;?>
                </p>
            </div>
        </div>
    </body>
</html>