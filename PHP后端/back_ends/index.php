<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
//*@后台登录操作
if(isset($_POST['do_login']) && $_POST['do_login']=="sub"){
  $db->user_login($_POST['username'], $_POST['password'], $_POST['verify_code']);
  unset($_POST);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>后台登录-Fz's Admin 2.0</title>
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
<script type="text/javascript">
function getcode(){
	document.getElementById("vdimgck").src="../public/verify.php?"+Math.random();
}
</script>
</head>

<body class="login-bg"> 
<div class="login layui-anim layui-anim-up">
	<div class="message">Fz's Admin 2.0-管理登录</div>
	<div id="darkbannerwrap"></div>

	<form method="post" class="layui-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input name="username" placeholder="用户名" type="text" lay-verify="required" class="layui-input" >
			<hr class="hr15">
			<input name="password" placeholder="密码" type="password" lay-verify="required"  class="layui-input">
			<hr class="hr15">
			<input name="verify_code" placeholder="验证码" type="text" lay-verify="required" class="layui-input">
			<span id="verify_code"><img id="vdimgck" align="absmiddle" name="safecode" onClick="this.src=this.src+'?'" style="cursor: pointer;" title="看不清？点击更换验证码" src="../public/verify.php"/></span>
			<hr class="hr15">	
			<input type="hidden" name="do_login" value="sub"> 		
           <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
       </form>
</div>
<script>
	$(function  () {
		layui.use('form', function(){
		  var form = layui.form;
		  form.on('submit(login)', function(data){ 
			$('form').submit();
			return false;
		  });
		});
	})	
</script>
</body>
</html>