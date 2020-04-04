<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>后台管理-Fz's Admin 2.0</title>
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="./style/css/font.css">
<link rel="stylesheet" href="./style/css/xadmin.css">
<script type="text/javascript" src="./style/js/jquery.min.js"></script>
<script type="text/javascript" src="./style/lib/layui/layui.js"></script>
<script type="text/javascript" src="./style/js/xadmin.js"></script>	
<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
  
  <body>
    <div class="x-body">
		<form class="layui-form" id="frm" action="" enctype="multipart/form-data" method="post">
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>二维码编号</label>
              <div class="layui-input-inline">
                  <input type="text" id="numbers" name="numbers" required="" lay-verify="numbers"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>收款二维码唯一的标识符,至少8位!
              </div>
          </div>
          <div class="layui-form-item">
              <label for="phone" class="layui-form-label">
                  <span class="x-red">*</span>二维码图片
              </label>
              <div class="layui-input-inline">
				  <input type="file" name="files" class="file" lay-verify="file">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>上传你的收款二维码图片,建议长200 高200的二维码图片
              </div>
          </div>

          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="add" lay-submit="">
                  增加
              </button>
			  <input type="hidden" name="submit" value="up"> 	
          </div>
      </form>
    </div>
<script>
layui.use(['form','layer'], function(){
	$ = layui.jquery;
	var form = layui.form
	,layer = layui.layer;


	//自定义验证规则
	form.verify({
		numbers: function(value){
			if(value.length < 8){
				return '收款二维码唯一的标识符不得少于8位';
			}
		}
		,file: function(value){
			if($(".file").val()==''){
				return '请点击选择一张收款码图片';
			}
		}
	});

	//监听提交
	form.on('submit(add)', function(data){
		document.getElementById("frm").submit()
		return false;
	});
});
</script>
<?php 
//上传二维码以及提交数据操作
if(isset($_POST['submit']) && $_POST['submit']=='up') {
	$order_number = $_POST['numbers'];//二维码编号
	$file= $_FILES['files'];
	$db->create_pay($order_number, $file);
}
?>
</body>
</html>
