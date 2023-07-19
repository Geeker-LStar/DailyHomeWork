<!--作业发布后台（设置特色图片）-->

<?php
    require_once("config.php");    // 配置文件
    require_once("functions.php");
?>

<?php
    session_start();
?>
<?php
    // 检查是否登录，未登录则要求登录
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<?php
    $magic_num = rand(100000, 999999);    // 随机数，保证每次显示的图片都是最新的（无论是否开启缓存）
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>设置特色图片 —— 作业发布后台</title>
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
    </head>
    
    <body>
        <div id="modal_div"></div>
        <h1 class="text-4xl font-semibold px-8 py-6">设置特色图片</h1>
        <strong class="px-8 py-4 font-semibold">支持 png/jpg/jpeg/gif 格式</strong>
        <p class="px-8 py-4">注 1：更新图片后需要刷新原网页才能看到效果。</p>
        <p class="px-8 pb-4">注 2：由于浏览器缓存等因素，首页显示的图片可能不会实时更新（班徽除外）。</p>
        <hr>
        <h3 class="text-xl font-semibold px-8 py-4">设置班徽（小于 200kb）</h3>
        <form action="set_imgs.php" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pb-8 mb-4">
            <input type="file" name="logo" class="mb-4"><br>
            <input type="submit" name="setlogo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">
        </form>
        <hr>
        <!--<h3 class="text-xl font-semibold px-8 py-4">设置页眉特色图片（小于 200kb）</h3>-->
        <!--<form action="set_imgs.php" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pb-8 mb-4">-->
        <!--    <input type="file" name="header" class="mb-4"><br>-->
        <!--    <input type="submit" name="setheader" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">-->
        <!--</form>-->
        <!--<hr>-->
        <h3 class="text-xl font-semibold px-8 py-4">设置查找作业页面的特色图片（首页展示）</h3>
        <form action="set_imgs.php" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pb-8 mb-4">
            <input type="file" name="search" class="mb-4"><br>
            <input type="submit" name="setsearch" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">
        </form>
        <hr>
        <h3 class="text-xl font-semibold px-8 py-4">设置某年某月的特色图片（首页展示）</h3>
        <form action="set_imgs.php" method="post"  enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pb-8 mb-4">
            <select name="year" class="border rounded" style="width: 100px;">
            <?php
                for ($i = 2020; $i < 2100; $i++)
                    echo "<option value=" . $i . ">" . $i . "</option>";
            ?>
            </select>
            <select name="month" class="border rounded ml-4" style="width: 100px;">
            <?php
                for ($i = 1; $i < 13; $i++)
                {
                    if ($i < 10)
                        echo "<option value=0" . $i . ">" . $i . "</option>";
                    else
                        echo "<option value=" . $i . ">" . $i . "</option>";
                }
            ?>
            </select>
            <br><br>
            <input type="file" name="feature" class="mb-4"><br>
            <input type="submit" name="setfeature" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="-webkit-appearance: none;">
        </form>
        <hr><br>
    </body>
</html>

<?php
    if (isset($_POST["setsearch"]))
    {
        if ((($_FILES["search"]["type"] == "image/gif")
        || ($_FILES["search"]["type"] == "image/jpeg")
        || ($_FILES["search"]["type"] == "image/jpg")
        || ($_FILES["search"]["type"] == "image/png"))) {
            if ($_FILES["search"]["error"] > 0)
            {
                $error = $_FILES["search"]["error"];
                echo "<script>showModal('fail', '错误：$error', 'set_imgs.php')</script>";
            }
                
            else
            {   
                $target_url = "hw-imgs/search.jpg";
                move_uploaded_file($_FILES["search"]["tmp_name"], $target_url);

                echo "<script>showModal('success', '设置查找作业页面的特色图片成功！<img src=\"$target_url?random=$magic_num\" class=\"m-auto my-4\" style=\"height: 170px; width: 250px;\">', 'set_imgs.php')</script>";
            }
        }
        else
            echo "<script>showModal('fail', '文件类型错误。', 'set_imgs.php')</script>";
    }
    
    if (isset($_POST["setlogo"]))
    {
        if ((($_FILES["logo"]["type"] == "image/gif")
        || ($_FILES["logo"]["type"] == "image/jpeg")
        || ($_FILES["logo"]["type"] == "image/jpg")
        || ($_FILES["logo"]["type"] == "image/png"))) {
            if ($_FILES["logo"]["error"] > 0)
            {
                $error = $_FILES["logo"]["error"];
                echo "<script>showModal('fail', '错误：$error', 'set_imgs.php')</script>";
            }
            elseif ($_FILES["logo"]["size"] > 204800)
                echo "<script>showModal('fail', '图片大于 200kb。', 'set_imgs.php')</script>";
            else
            {   
                $target_url = "hw-imgs/logo.jpg";
                move_uploaded_file($_FILES["logo"]["tmp_name"], $target_url);

                echo "<script>showModal('success', '设置班徽成功！<img src=\"$target_url?random=$magic_num\" class=\"m-auto my-4\" style=\"height: 170px; width: 170px;\">刷新以查看效果。', 'set_imgs.php')</script>";
            }
        }
        else
            echo "<script>showModal('fail', '文件类型错误。', 'set_imgs.php')</script>";
    }
    
    // if (isset($_POST["setheader"]))
    // {
    //     if ((($_FILES["header"]["type"] == "image/gif")
    //     || ($_FILES["header"]["type"] == "image/jpeg")
    //     || ($_FILES["header"]["type"] == "image/jpg")
    //     || ($_FILES["header"]["type"] == "image/png"))) {
    //         if ($_FILES["header"]["error"] > 0)
    //         {
    //             $error = $_FILES["header"]["error"];
    //             echo "<script>showModal('fail', '错误：$error', 'set_imgs.php')</script>";
    //         }
    //         elseif ($_FILES["header"]["size"] > 204800)
    //             echo "<script>showModal('fail', '图片大于 200kb。', 'set_imgs.php')</script>";
    //         else
    //         {   
    //             $target_url = "hw-imgs/header_img.jpg";
    //             move_uploaded_file($_FILES["header"]["tmp_name"], $target_url);

    //             echo "<script>showModal('success', '设置页眉特色图片成功！<img src=\"$target_url?random=$magic_num\" class=\"m-auto my-4\">刷新以查看效果。', 'set_imgs.php')</script>";
    //         }
    //     }
    //     else
    //         echo "<script>showModal('fail', '文件类型错误。', 'set_imgs.php')</script>";
    // }
    
    if (isset($_POST["setfeature"]))
    {
        if ((($_FILES["feature"]["type"] == "image/gif")
        || ($_FILES["feature"]["type"] == "image/jpeg")
        || ($_FILES["feature"]["type"] == "image/jpg")
        || ($_FILES["feature"]["type"] == "image/png"))) {
            if ($_FILES["feature"]["error"] > 0)
            {
                $error = $_FILES["search"]["error"];
                echo "<script>showModal('fail', '错误：$error', 'set_imgs.php')</script>";
            }
            else
            {   
                $year = $_POST["year"];
                $month = $_POST["month"];
                $target_url = "hw-imgs/" . $year . "_" . $month . ".jpg";
                move_uploaded_file($_FILES["feature"]["tmp_name"], $target_url);
                
                echo "<script>showModal('success', '设置 $year 年 $month 月的特色图片成功！<img src=\"$target_url?random=$magic_num\" class=\"m-auto my-4\" style=\"height: 170px; width: 250px;\">', 'set_imgs.php')</script>";
            }
        }
        else
            echo "<script>showModal('fail', '文件类型错误。', 'set_imgs.php')</script>";
    }
?>