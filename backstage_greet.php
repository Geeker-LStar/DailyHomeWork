<!--作业发布后台（默认页面）-->

<?php
    session_start();
?>
<?php
    // 检查是否登录，未登录则要求登录
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<?php
    require_once("./config.php");
?>


<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>欢迎页面 - 作业发布后台</title>
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <link rel="stylesheet" href="hw-css/footer.css">
    </head>
    
    <body>
        <div class="bg-yellow-50" style="padding: 10%; margin: 12%;">
            <center><h1 class="text-4xl font-semibold mb-6">欢迎来到作业发布网站后台！</h2>
            <p class="mb-2">您可在左侧侧边栏选择下一步操作。</p>
            <p class="mb-2">您可在 “修改配置” 中修改网站基本信息。</p>
            <p class="mb-2">如有任何疑问/建议/意见/反馈，请点击右下角 “铅笔” 图标并填写反馈内容。</p></center>
        </div>
        
        <div class="footer" style="position: fixed; bottom: 5px; width: 100%;">
            <h2 class="text-2xl font-semibold mb-4"><?php echo $footer;?></h2>
            <p><?php echo $love_thanks;?></p>
        </div>
    </body>
</html>