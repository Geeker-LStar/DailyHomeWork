<!--作业发布后台（配置邮件服务）-->

<?php
    require_once("config.php");
    require_once("functions.php");
?>

<?php
    $email_config = json_decode($email_json, true);
    $server = $email_config["Host"];
    $password = $email_config["Password"];
    $secure = $email_config["SMTPSecure"];
    $port = $email_config["Port"];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
        <title>配置邮件服务 —— 作业发布后台</title>
    </head>
    
    <body>
        <div id="modal_div"></div>
        <h1 class="text-4xl font-semibold px-8 py-6">配置邮件服务（SMTP）</h1>
        <strong class="px-8 py-4 font-semibold">订阅等功能只有在配置 SMTP 后才可使用。</strong>
        <form action="email_service.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <label class="block text-gray-700 text-base font-bold my-2" for="address">
                发件邮箱：
            </label>
            <input type="email" name="address" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $semail;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="sendername">
                发件时使用的名称：
            </label>
            <input type="text" name="sendername" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $sname;?>>（如：xx 班作业发布网站）
            <label class="block text-gray-700 text-base font-bold my-2" for="smtp_server">
                SMTP 服务器：
            </label>
            <input type="text" name="smtp_server" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $server;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="smtp_pwd">
                SMTP 授权码：
            </label>
            <input type="text" name="smtp_pwd" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $password;?>>
            <label class="block text-gray-700 text-base font-bold my-2" for="smtp_secure">
                SMTP 加密方式：
            </label>
            <input type="text" name="smtp_secure" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 my-2 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $secure;?>>（ssl / tls）
            <label class="block text-gray-700 text-base font-bold my-2" for="smtp_port">
                SMTP 端口号：
            </label>
            <input type="text" name="smtp_port" style="width: 400px;" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" value=<?php echo $port;?>>
            <div class="flex items-center justify-between">
                <input type="submit" name="set_smtp" value="配置 SMTP 邮件服务" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </body>
</html>

<?php
    if (isset($_POST["set_smtp"]))
    {
        $semail = $_POST["address"];    // 发件邮箱
        $sname = $_POST["sendername"];    // 发件名称
        $email_config["Host"] = $_POST["smtp_server"];    // smtp 服务器
        $email_config["Username"] = $_POST["address"];
        $email_config["Password"] = $_POST["smtp_pwd"];    // smtp 授权码
        $email_config["SMTPSecure"] = $_POST["smtp_secure"];    // smtp 加密方式
        $email_config["Port"] = $_POST["smtp_port"];    // smtp 端口号
        $new_json = json_encode($email_config);
        
        modify_configs("semail = ", $semail);
        modify_configs("sname = ", $sname);
        modify_configs("remail = ", $semail);
        modify_configs("email_json = ", $new_json);
        
        echo "<script>showModal('success', '邮件服务信息修改成功！', 'email_service.php')</script>";
    }
?>