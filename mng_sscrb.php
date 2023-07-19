<!--作业发布后台（管理订阅者）-->

<?php
    session_start();
?>

<?php
    // 检查是否登录，未登录则要求登录
    if (!isset($_SESSION['user']))
        header("Location: login.php");
?>

<?php
    require_once("config.php");    // 配置文件
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <title>管理订阅者 —— 作业发布后台</title>
    </head>
    
    <body>
        <div id="modal_div"></div>
        <h1 class="text-4xl font-semibold px-8 py-6">管理订阅者</h1>
        <strong class="px-8 py-4 font-semibold">删除订阅者后无法恢复，请谨慎操作。</strong>
        <table class="border-separate border border-yellow-800 mx-8 mt-6">
            <tr>
                <th class="border border-yellow-600 px-4">昵称</th>
                <th class="border border-yellow-600 px-4">邮箱</th>
                <th class="border border-yellow-600 px-4">订阅时间</th>
                <th class="border border-yellow-600 px-4">是否接收邮件</th>
                <th class="border border-yellow-600 px-4">操作</th>
            </tr>
        <?php
            $sql = "SELECT * FROM {$prefix}Subscribers ORDER BY id DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0)
            {
                while ($subscrb = $result->fetch_assoc())
                {
                    $name = $subscrb["name"];
                    $email = $subscrb["email"];
                    $time = $subscrb["time"];
                    $is_r = $subscrb["is_receive"];
                    echo "<tr>";
                    echo "<td class='border border-yellow-600 px-4'>$name</td>";
                    echo "<td class='border border-yellow-600 px-4'>$email</td>";
                    echo "<td class='border border-yellow-600 px-4'>$time</td>";
                    echo "<td class='border border-yellow-600 px-4'>$is_r</td>";
                    echo "<form action='mng_sscrb.php' method='post'>";
                    echo "<input type='hidden' name='sscrb' value='$email'>";
                    echo "<td><input type='submit' name='delete' value='删除' class='border border-yellow-600 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'></td>";
                    echo "</tr>";
                    echo "</form>";
                }
            }
        ?>
        </table><br><br>
    </body>
</html>

<?php
    if (isset($_POST["delete"]))
    {
        $email = $_POST["sscrb"];
        echo "<script>showModal('warning', '你真的要删除该订阅者吗？此操作无法撤销。', 'mng_sscrb.php?delete_email=$email', 'mng_sscrb.php')</script>";
    }
?>
<?php
    if (isset($_GET["delete_email"]))
    {
        $email = $_GET["delete_email"];
        $sql = "DELETE FROM {$prefix}Subscribers WHERE email='{$email}'";
        $conn->query($sql);
        echo "<script>showModal('success', '已成功删除订阅者 $email', 'mng_sscrb.php')</script>";
    }
?>