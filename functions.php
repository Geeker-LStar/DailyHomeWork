<!--重要函数（勿动！！）-->

<?php
    // 该函数修改配置文件中的 key_value 键值对
    // $string：要修改的项的键
    // $new_value：要修改的项的值
    // ⭐声明：如果您没有一定的编程经验或您不理解该函数的逻辑，请不要贸然修改该函数，否则会导致修改配置文件时出错⭐
    
    function modify_configs($string, $new_value)    // $string 的形式：$key = 
    {
        $configs = file_get_contents("config.php");    // 将 config.php 中的内容读入字符串
        $start = strpos($configs, $string);    // 一个 key-value 在文件中的开始位置（开始字符的位置）
        $end = strpos($configs, ";", $start);    // key-value 在文件中的结束位置
        $len = strlen($string);    // $string 的长度（$string 起始位置 $start + $string 长度 $len = 要修改的第一个位置
        $modify_content = "'" . $new_value . "'";    // 要修改的部分的内容
        $new_content = substr($configs, 0, $start + $len) . $modify_content . substr($configs, $end);    // 新的文件内容
        
        $handle = fopen("config.php", "w");    // 打开文件
        fwrite($handle, $new_content);    // 写入文件
        fclose($handle);    // 关闭
    }
?>

<?php
    function textarea_line_break($string_need_to_break)
    {
        $arr = explode("\n", $string_need_to_break);
        for ($i = 0; $i < count($arr); $i++)
        {
            if ($i == count($arr)-1 || $i == count($arr)-2) {
                if ($arr[$i] != "")
                    $arr[$i] = $arr[$i] . "<br>";
            }
            else
                $arr[$i] = $arr[$i] . "<br>";
                
            $new_str = $new_str . $arr[$i];
        }
        return $new_str;
    }
?> 

<?php
    function delete_br_break($string_need_to_delete)
    {
        $arr = explode("<br>", $string_need_to_delete);
        for ($i = 0; $i < count($arr); $i++)
        {
            if ($i == count($arr)-1) {
                if ($arr[$i] != "")
                    $arr[$i] = $arr[$i] . "\n";
            }
            else
                $arr[$i] = $arr[$i] . "\n";
                
            $new_str = $new_str . $arr[$i];
        }
        return $new_str;
    }
?>

<?php
    // 退出登录
    
    function log_out()
    {
        session_unset();
        session_destroy();
        header("Location: login.php");
    }
?>

<script>
    // 打开模态框
    // type：打开的模态框类型（成功 / 警告 / 失败）
    // dscrpt：文字描述（如：xxxx 修改成功！）
    // link：点击关闭模态框后要跳转到的链接
    function showModal(type, dscrpt, link, link2)
    {   
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("modal_div").innerHTML = xmlhttp.responseText;
            }
        }
        var request_url = "modals.php?modal_type="+type+"&description="+dscrpt+"&link="+link+"&link2="+link2;
        xmlhttp.open("GET", request_url, true);
        xmlhttp.send();
    }
</script>