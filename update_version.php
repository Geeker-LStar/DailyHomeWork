<!--DHW 每日作业（升级）-->
<?php
    // ⭐注意：请勿修改该文件！否则后果自负！
    // **
    // * 该文件用于升级产品版本
    // **
?>

<?php
    if (isset($_POST["update"]) && trim($_POST["version"]) == "0.0.0")
        echo "<script>history.back(-1);</script>";
    elseif (isset($_POST["update"]) && trim($_POST["version"]) != "0.0.0")
    {
        $version = trim($_POST["version"]);
        $url = "https://dailyhw.ltx1102.com/dhw-versions/DailyHomeWork_" . $version . ".tar.gz";
        $zipname = "DailyHomeWork_" . $version . ".tar.gz";    // 下载的压缩包的名称
        $tempcontent = "./newversion";    // 临时解压到的目录的名称
?>

<?php
    $path = "version.update";
    fopen($path, "w");
?>

<?php
    //清除之前的缓冲内容
    ob_end_clean();
    //设置响应头连接状态为关闭
    header("Connection: close");
    //设置HTTP请求状态为200 OK
    header("HTTP/1.1 200 OK");
    //返回数据格式
    header("Content-Type: application/json;charset=utf-8");
    //打开输出控制缓冲
    ob_start();
    //返回数据给前端
    echo "<h1>网站正在升级！请【关闭本页面】并于 3-5 min 后访问网站！</h1>";
    //下面输出http的一些头信息
    $size = ob_get_length();
    //返回输出缓冲区内容的长度
    header("Content-Length: $size");
    //输出当前缓冲，并关闭缓冲
    ob_end_flush();
    //输出PHP缓冲
    flush();
    //前端接收到数据，请求到此结束，之后的代码都在后台静默执行，前端也无法知道执行情况
    if (function_exists("fastcgi_finish_request")) { // yii或yaf默认不会立即输出，加上此句即可（前提是用的fpm）
        fastcgi_finish_request(); // 响应完成, 立即返回到前端,关闭连接
    }
    //设置客户端断开连接时是否中断脚本的执行
    ignore_user_abort(true);
    //设置请求时间为不限时
    set_time_limit(0);
    
    $ch = curl_init($url);
    // 初始化保存文件的目录名
    $dir = './';
    // 函数返回文件名
    $file_name = basename($url);
    // 将文件保存到指定位置
    $save_file_loc = $dir . $file_name;
    // 打开文件
    $fp = fopen($save_file_loc, 'wb');
    // 为 curl 传输设置一个选项
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // 执行 curl 对话
    curl_exec($ch);
    // 关闭会话并释放所有资源
    curl_close($ch);
    // 关闭文件
    fclose($fp);
    echo('成功');
?>

<!--解压类-->
<?php
    class ZipUtil
    {
        /**
         * 解压
         * @param string $zipFilePath 压缩文件路径
         * @param string $toDirPath 解压目录路径
         * @return string
         * @throws \Exception
         */
         
        public static function extract(string $zipFilePath, string $toDirPath)
        {
            $toDirPath = rtrim($toDirPath, '/');
            self::deleteDir($toDirPath, false);
            if (!is_file($zipFilePath)) throw new \Exception('文件不存在。');
            if (!is_dir($toDirPath)) {
                mkdir($toDirPath, 0777, true);
            }
            $zipFilePathInfo = pathinfo($zipFilePath);
            $zipExt = pathinfo($zipFilePath, PATHINFO_EXTENSION);
            
            switch ($zipExt) {
                case 'zip':
                    if (!class_exists('\ZipArchive')) throw new \Exception('缺少解压 zip 的代码。');
                    $zipArch = new \ZipArchive();
                    if ($zipArch->open($zipFilePath) !== true) throw new \Exception('解压失败。');
                    //$zipArch->extractTo($toDirPath); //这个中文会乱码
                    //解决中文会乱码
                    $fileNum = $zipArch->numFiles;
                    for ($i = 0; $i < $fileNum; ++$i) {
                        $statInfo = $zipArch->statIndex($i, \ZipArchive::FL_ENC_RAW);
                        $statInfo['name'] = self::convertToUtf8($statInfo['name']);
                        //print_r($statInfo);
                        if ($statInfo['crc'] === 0 && $statInfo['name'][strlen($statInfo['name']) - 1] === '/') {
                            $dirPath = $toDirPath . '/' . $statInfo['name'];
                            if (!is_dir($dirPath)) {
                                mkdir($dirPath, 0777, true);
                            }
                        } else {
                            copy('zip://' . $zipFilePath . '#' . $zipArch->getNameIndex($i), $toDirPath . '/' . $statInfo['name']);
                        }
                    }
                    $zipArch->close();
                    break;
                case 'rar':
                    if (!class_exists('\RarArchive')) throw new \Exception('缺少解压 rar 的代码。');
                    $rarArch = \RarArchive::open($zipFilePath);
                    if ($rarArch === false) throw new \Exception('解压失败。');
                    $entries = $rarArch->getEntries();
                    if ($entries === false) throw new \Exception('解压失败。');
                    foreach ($entries as $entry) {
                        $entry->extract($toDirPath);
                    }
                    $rarArch->close();
                    break;
                case 'phar':
                    if (!class_exists('\Phar')) throw new \Exception('缺少解压 phar 的代码。');
                    $phar = new \Phar($zipFilePath, null, null);
                    $extract = $phar->extractTo($toDirPath, null, true);
                    if (!isset($zipFilePathInfo['extension'])) {
                        unlink($zipFilePath);
                    }
                    if ($extract === false) throw new \Exception('解压失败。');
                    break;
                case 'tar':
                case 'gz':
                case 'bz2':
                    if (!class_exists('\PharData')) throw new \Exception('缺少解压 phar 的代码。');
                    $formats = [
                        'tar' => \Phar::TAR,
                        'gz' => \Phar::GZ,
                        'bz2' => \Phar::BZ2,
                    ];
                    $format = $formats[$zipExt];
                    $phar = new \PharData($zipFilePath, null, null, $format);
                    $extract = $phar->extractTo($toDirPath, null, true);
                    if (!isset($zipFilePathInfo['extension'])) {
                        unlink($zipFilePath);
                    }
                    if ($extract === false) throw new \Exception('解压失败。');
                    break;
                case '7z':
                    if(shell_exec('type 7z') === null) throw new \Exception('缺少解压 p7zip 的代码。');
                    $cmd = '7z x ' . $zipFilePath . ' -r -o' . $toDirPath;
                    $result = shell_exec($cmd);
                    break;
                default;
                    throw new \Exception('不支持的解压格式。');
            }
            return $toDirPath;
        }
        
        /**
         * 压缩多个文件
         * @param array $files 文件列表，格式：[['file_type'=>'file|folder', 'file_path'=>'/a/b/test.txt', 'local_name'=>'b/test.txt']]
         * @param string $toFilePath 压缩文件路径
         * @return string
         * @throws \Exception
         */
    
        public static function package(array $files, string $toFilePath)
        {
            $toFilePathInfo = pathinfo($toFilePath);
            if (!is_dir($toFilePathInfo['dirname'])) {
                mkdir($toFilePathInfo['dirname'], 0777, true);
            }
            $zipArch = new \ZipArchive();
            if ($zipArch->open($toFilePath, \ZipArchive::CREATE) !== true) throw new \Exception('压缩失败。');
            foreach ($files as $file) {
                if ($file['file_type'] === 'folder') {
                    $zipArch->addEmptyDir(ltrim($file['local_name'], '/'));
                } else if ($file['file_type'] === 'file') {
                    if (is_file($file['file_path'])) {
                        $zipArch->addFile($file['file_path'], $file['local_name']);
                    }
                }
            }
            $zipArch->close();
            return $toFilePath;
        }

        /**
         * 压缩文件夹
         * @param string $dirPath 要压缩的文件夹路径
         * @param string $toFilePath 压缩文件路径
         * @param bool $includeSelf 是否包含自身
         * @return string
         * @throws \Exception
         */
    
        public static function packageDir(string $dirPath, string $toFilePath, bool $includeSelf = true)
        {
            if (!is_dir($dirPath)) throw new \Exception('文件夹不存在。');
            $toFilePathInfo = pathinfo($toFilePath);
            if (!is_dir($toFilePathInfo['dirname'])) {
                mkdir($toFilePathInfo['dirname'], 0777, true);
            }
            $dirPathInfo = pathinfo($dirPath);
            //print_r($dirPathInfo);
            $zipArch = new \ZipArchive();
            if ($zipArch->open($toFilePath, \ZipArchive::CREATE) !== true) throw new \Exception('压缩失败。');
            $dirPath = rtrim($dirPath, '/') . '/';
            $filePaths = self::scanDir($dirPath);
            if ($includeSelf) {
                array_unshift($filePaths, $dirPath);
            }
            //print_r($filePaths);
            foreach ($filePaths as $filePath) {
                $localName = mb_substr($filePath, mb_strlen($dirPath) - ($includeSelf ? mb_strlen($dirPathInfo['basename']) + 1 : 0));
                //echo $localName . PHP_EOL;
                if (is_dir($filePath)) {
                    $zipArch->addEmptyDir($localName);
                } else if (is_file($filePath)) {
                    $zipArch->addFile($filePath, $localName);
                }
            }
            $zipArch->close();
            return $toFilePath;
        }
        
        /**
         * 压缩单个文件
         * @param string $filePath 要压缩的文件路径
         * @param string $toFilePath 压缩文件路径
         * @return string
         * @throws \Exception
         */
    
        public static function packageFile(string $filePath, string $toFilePath)
        {
            if (!is_file($filePath)) throw new \Exception('文件不存在。');
            $toFilePathInfo = pathinfo($toFilePath);
            if (!is_dir($toFilePathInfo['dirname'])) {
                mkdir($toFilePathInfo['dirname'], 0777, true);
            }
            $filePathInfo = pathinfo($filePath);
            $zipArch = new \ZipArchive();
            if ($zipArch->open($toFilePath, \ZipArchive::CREATE) !== true) throw new \Exception('压缩失败。');
            $zipArch->addFile($filePath, $filePathInfo['basename']);
            $zipArch->close();
            return $toFilePath;
        }
    
        /**
         * 字符串转为UTF8字符集
         * @param string $str
         * @return false|string
         */
    
        private static function convertToUtf8(string $str)
        {
            $charset = mb_detect_encoding($str, ['UTF-8', 'GBK', 'BIG5', 'CP936']);
            if ($charset !== 'UTF-8') {
                $str = iconv($charset, 'UTF-8', $str);
            }
            return $str;
        }
        
        /**
         * 删除目录以及子目录等所有文件
         *
         * 请注意不要删除重要目录！
         *
         * @param string $path 需要删除目录路径
         * @param bool $delSelf 是否删除自身
         */
    
        private static function deleteDir(string $path, bool $delSelf = true)
        {
            if (!is_dir($path)) {
                return;
            }
            $dir = opendir($path);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $full = $path . '/' . $file;
                    if (is_dir($full)) {
                        self::deleteDir($full, true);
                    } else {
                        unlink($full);
                    }
                }
            }
            closedir($dir);
            if ($delSelf) {
                rmdir($path);
            }
        }

        /**
         * 遍历文件夹，返回文件路径列表
         * @param string $path
         * @param string $fileType all|file|folder
         * @param bool $traversalChildren 是否遍历下级目录
         * @return array
         */
         
        private static function scanDir(string $path, string $fileType = 'all', bool $traversalChildren = true)
        {
            if (!is_dir($path) || !in_array($fileType, ['all', 'file', 'folder'])) {
                return [];
            }
            $path = rtrim($path, '/');
            $list = [];
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $p = $path . '/' . $file;
                    $isDir = is_dir($p);
                    if ($isDir) {
                        $p .= '/';
                    }
                    if ($fileType === 'all' || ($fileType === 'file' && !$isDir) || ($fileType === 'folder' && $isDir)) {
                        $list[] = $p;
                    }
                    if ($traversalChildren && $isDir) {
                        $list = array_merge($list, self::scanDir($p, $fileType, $traversalChildren));
                    }
                }
            }
            return $list;
        }
    }
    
    ZipUtil::extract($zipname, $tempcontent);
?>

<!--对 config.php 的处理（函数）-->
<?php
    function write_cfg($path, $string, $new_value)    // $string 的形式：$key = 
    {
        $configs = file_get_contents($path);    // 将 config.php 中的内容读入字符串
        $start = strpos($configs, $string);    // 一个 key-value 在文件中的开始位置（开始字符的位置）
        $end = strpos($configs, ";", $start);    // key-value 在文件中的结束位置
        $len = strlen($string);    // $string 的长度（$string 起始位置 $start + $string 长度 $len = 要修改的第一个位置
        $modify_content = explode(";", $new_value)[0];    // 要修改的部分的内容
        $new_content = substr($configs, 0, $start + $len) . $modify_content . substr($configs, $end);    // 新的文件内容
    
        $handle = fopen($path, "w");
        fwrite($handle, $new_content);    // 写入文件
        fclose($handle);    // 关闭
    }
    
    function upd_cfg($src, $dst)
    {
        $old_path = $src . '/config.php';
        $handle = fopen($old_path, "r");
        $old_contents = fread($handle, filesize($src . '/config.php'));    // 获取文件内容
        fclose($handle);
    
        // $pattern = "#\\\$.*=.*.*;#";
        $pattern = "/\\\$[a-zA-Z0-9_\s]*=(\s[\"']|[\"'])/";
        preg_match_all($pattern, $old_contents, $old_arr);
        for ($i = 0; $i < count($old_arr[0]); $i++)
        {
            preg_match_all("#\\\$[a-zA-Z0-9_]*#", $old_arr[0][$i], $_old_key);
            $old_key[$i] = $_old_key[0][0];
            $start = strpos($old_contents, $old_key[$i]);
            $end = strpos($old_contents, ";", $start)-1;
            $full_line = substr($old_contents, $start, $end - $start + 2);
            preg_match_all("#[\"'][\s\S]*[\"'];#", $full_line, $_old_value);
            $old_value[$i] = $_old_value[0][0];
        }
        
        $new_path = $dst . '/config.php';
        $handle = fopen($new_path, "r");
        $new_contents = fread($handle, filesize($dst . '/config.php'));    // 获取文件内容
        fclose($handle);
    
        $pattern = "/\\\$[a-zA-Z0-9_\s]*=(\s[\"']|[\"'])/";
        preg_match_all($pattern, $new_contents, $arr);
        for ($i = 0; $i < count($arr[0]); $i++)
        {
            preg_match_all("#\\\$[a-zA-Z0-9_]*#", $arr[0][$i], $_key);
            $key[$i] = $_key[0][0];
            $start = strpos($new_contents, $key[$i]);
            $end = strpos($new_contents, ";", $start)-1;
            $full_line = substr($new_contents, $start, $end - $start + 2);
            preg_match_all("#[\"'][\s\S]*[\"'];#", $full_line, $_value);
            $value[$i] = $_value[0][0];
        }
        
        for ($i = 0; $i < count($key); $i++)    // 在新版的 key 中遍历
        {
            if (array_search($key[$i], $old_key)+1)    // 如果这个 key 在旧版中也出现
            {
                $index = array_search($key[$i], $old_key);    // 找到这个 key 在旧版中的位置
                if ($old_value[$index] != null && $key[$i] != "\$version")
                    write_cfg($new_path, $old_key[$index] . " = ", $old_value[$index]);
            }
        }
    }
?>


<!--将文件从源目录复制到目标目录（并删除源目录）（函数）-->
<?php
    function recurse_copy($src, $dst)    // 源目录，目标目录
    { 
        $dir = opendir($src);    // 打开源目录（读取文件）
        if (!file_exists($dst)) {
            mkdir($dst);    // 创建目标目录
        }
        while (false !== ($file = readdir($dir)))    // 文件未被读取完毕时（$file 是被读取的文件的名称）
        {
            if (($file != '.') && ($file != '..') && ($file != 'config.php'))
            {
                if (is_dir($src . '/' . $file)) {    // 如果读取的是一个目录（哈哈，按照 Linux 的理念，目录也是文件（（（bushi
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);    // 递归
                }
                else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
            elseif ($file == 'config.php')
            {
                upd_cfg(".", "./newversion");
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
        closedir($dir);
    }
    
    function remove_dir($path)
    {
        $dir = opendir($path);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..'))
            {
                if (is_dir($path . '/' . $file)) {    // 是目录 
                    remove_dir($path . '/' . $file);    // 递归删除
                }
                else
                    unlink($path . '/' . $file);
            }
        }
        rmdir($path);
    }
    
    recurse_copy($tempcontent, "./");
    remove_dir($tempcontent);
    unlink("version.update");
    unlink("versions.txt");
    unlink($zipname);
?>

<!--保险措施——如果新的 config.php 文件中没有 $version 项或 $version 项为空或 $version 项不等于最新的版本号，则添加或更新 $version 项（函数）-->
<?php
    // 算了，还是不用那么多判断了，干脆直接再更新一边，这样我就不怕我犯傻了（（（
    // 好吧好吧，其实就是懒得写那——么——复杂——的——判断。。。
    function check_version($vs)
    {
        $path = "./config.php";
        write_cfg($path, "\$version = ", "'" . $vs . "'");
    }
    
    check_version($version);
?>

<?php
    }
?>