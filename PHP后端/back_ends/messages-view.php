<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

$mess_id = isset($_GET['mess_id']) ? $_GET['mess_id'] : '';
if($mess_id){
	$id=(int)$mess_id; 
	$mess_data = get_one_mess($id);//根据ID取一条站内消息数据
}

?>
<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./style/css/font.css">
    <link rel="stylesheet" href="./style/css/xadmin.css">
    <script type="text/javascript" src="./style/js/jquery.min.js"></script>
    <script type="text/javascript" src="./style/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./style/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="x-body">
		<div class="layui-form-item layui-form-text">
		  <label for="desc" class="layui-form-label">
			  消息标题:
		  </label>
		  <div class="layui-input-block">
			  <table class="layui-table">
				<tbody>
				  <tr>
					<td><?php echo $mess_data['title'];?></div></td>
				  </tr>
				  <tr>
				</tbody>
			  </table>
		  </div>

		<div class="layui-form-item layui-form-text">
		  <label for="desc" class="layui-form-label">
			  消息内容:
		  </label>
		  <div class="layui-input-block">
			  <table class="layui-table">
				<tbody>
				  <tr>
					<td><?php echo $mess_data['content'];?></div></td>
				  </tr>
				  <tr>
				</tbody>
			  </table>
		</div>
		
		<div class="layui-form-item layui-form-text">
		  <label for="desc" class="layui-form-label">
			  发布日期:
		  </label>
		  <div class="layui-input-block">
			  <table class="layui-table">
				<tbody>
				  <tr>
					<td><?php echo date('Y-m-d H:i:s' ,$mess_data['add_time']);?></div></td>
				  </tr>
				  <tr>
				</tbody>
			  </table>
		</div>
    </div>
  </body>
</html>