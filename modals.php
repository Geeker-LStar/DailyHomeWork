<!--作业发布后台（模态弹出框）-->

<?php
    if (isset($_GET["modal_type"]))
    {
        $modal_type = $_GET["modal_type"];    // 模态框类型
        $text = $_GET["description"];    // 说明文字
        $link = $_GET["link"];    // 关闭后跳转到的链接
        $link2 = $_GET["link2"];
    }
?>

<!DOCTYPE html>
<head>
    <title>模态弹出框样式 —— 作业发布后台</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="hw-includes/node_modules/tailwindcss/dist/tailwind.min.css">
</head>

<body>
    <!--成功模态框-->
    <?php
        if ($modal_type == "success") {
    ?>
    <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="success_modal" style="display: block;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <img src="hw-imgs/dhw_modal_success.png" class="mx-auto mb-4" style="height: 80px; width: 80px;">
	            <h3 class="text-lg leading-6 font-medium text-gray-900">成功！</h3>
        		<div class="mt-2 px-7 py-3">
        			<p class="text-sm text-gray-500">
        				<?php echo $text;?>
        			</p>
        		</div>
	            <div class="items-center px-4 py-3">
		        <button id="ok_btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300" onclick="window.location.href='<?php echo $link;?>';">
			        OK
		        </button>
	            </div>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    
    <!--警告模态框-->
    <?php
        if ($modal_type == "warning") {
    ?>
    <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="warning_modal" style="display: block;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <img src="hw-imgs/dhw_modal_warning.png" class="mx-auto mb-4" style="height: 80px; width: 80px;">
	            <h3 class="text-lg leading-6 font-medium text-gray-900">警告！</h3>
        		<div class="mt-2 px-7 py-3">
        			<p class="text-sm text-gray-500">
        				<?php echo $text;?>
        			</p>
        		</div>
	            <div class="items-center px-4 py-3 grid grid-cols-2">
		        <button id="ok_btn" class="px-4 py-2 text-yellow-500 text-base font-medium rounded-md shadow-sm hover:text-yellow-600 focus:outline-none ring-2 ring-yellow-300 mr-2" onclick="window.location.href='<?php echo $link;?>';">
			        确认
		        </button>
		        <button id="ok_btn" class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none ml-2" onclick="window.location.href='<?php echo $link2;?>';">
			        取消
		        </button>
	            </div>
            </div>
        </div>
    </div>
    <?php 
        }
    ?>
    
    <!--失败模态框-->
    <?php
        if ($modal_type == "fail") {
    ?>
    <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="fail_modal" style="display: block;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <img src="hw-imgs/dhw_modal_fail.png" class="mx-auto mb-4" style="height: 80px; width: 80px;">
	            <h3 class="text-lg leading-6 font-medium text-gray-900">失败！</h3>
        		<div class="mt-2 px-7 py-3">
        			<p class="text-sm text-gray-500">
        				<?php echo $text;?>
        			</p>
        		</div>
	            <div class="items-center px-4 py-3">
		        <button id="ok_btn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-rd-600 focus:outline-none focus:ring-2 focus:ring-red-300" onclick="window.location.href='<?php echo $link;?>';">
			        关闭
		        </button>
	            </div>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
</body>