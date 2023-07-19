<!--作业发布后台（登录）-->

<?php
    $time_limit = 60*60*24*15;    // 过期时间为十五天
    session_set_cookie_params($time_limit);
    session_start();
?>

<?php
    require("config.php");
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
        <title>登录 - 作业发布后台</title>
        <link rel="stylesheet" href="hw-includes/node_modules/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="hw-css/login.css">
    </head>
    
    <body>
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
        
            @media (min-width: 768px) {
                    .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
        
        <?php
            if (!isset($_SESSION["user"]))
            {
                if (isset($_POST["login"]))
                {
                    $usern = $_POST["usern"];
                    $pwd = $_POST["pwd"];
                    $sql = "SELECT * FROM {$prefix}Users WHERE username='{$usern}' and password='{$pwd}'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0)
                    {
                        $_SESSION["user"] = $usern;
                        $conn->close();
                        header("Location: backstage.php");
                    }
                        
                    else
                        header("Location: login.php?state=failed");
                }
            }
            else
                header("Location: backstage.php");
            
            if (isset($_GET["state"]) && $_GET["state"] == "failed")
                echo "<script>alert('登录失败：用户名或密码错误。');</script>";
        ?>
        <div class="form-signin">
            <form action="login.php" method="post">
                <center><img class="mb-4" src="hw-imgs/logo.jpg" alt="" width="96" height="96"></center>
                <h1 class="h3 mb-3 fw-normal"><center>登录到作业发布后台</center></h1>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="usern" placeholder="username">
                    <label for="floatingInput">用户名</label>
                </div>
                <br>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="pwd" placeholder="password">
                    <label for="floatingPassword">密码</label>
                </div>
                <br>
                <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">登录</button>
                <p class="mt-5 mb-3 text-muted">&copy; <?php echo $all_years;?> <?php echo $class_short_name;?></p>
            </form>
        </div>
        
    </body>
</html>