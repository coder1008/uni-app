<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/public/global.php';

url_allow();//设置允许远程跨域

//取站点配置信息
$web_data = get_web_config(1);//取站点配置信息
$web_config = unserialize($web_data['content']);//反序列化

url_allow();//设置允许远程跨域

//Ajax登录处理
if(isset($_POST['submit']) && $_POST['submit']=='login') {
	$user = trim($_POST['user']);
	$pass = trim($_POST['pass']);
	$db->member_dologin($user, $pass);//会员登录
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $web_config['web_name'];?> | 会员登录</title>
	<meta name="keywords" content="<?php echo $web_config['web_key'];?>" />
	<meta name="description" content="<?php echo $web_config['web_des'];?>" />		
    <!-- Bootstrap -->
    <link href="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<!-- layui插件 -->
	<script type="text/javascript" src="<?php echo INDEX_STYLE;?>/layui/layui.js"></script>
	<script type="text/javascript" src="<?php echo INDEX_STYLE;?>/layui/layui.all.js"></script>
  </head>
  <body>

<nav class="navbar navbar-inverse navbar-static-top">
  <div class="container-fluid">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/">网站首页<span class="sr-only">(current)</span></a></li>
        <!--<li><a href="#">新闻中心</a></li>-->
      </ul>
	<?php include_once('header.php'); ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



<div class="container-fluid">
  <div class="row-fluid">
  <h2 class="sub-header">会员登录</h2>
    <form class="form-horizontal layui-form">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">登录账户</label>
        <div class="col-xs-3">
          <input type="text" class="form-control" id="user" placeholder="Email" required="" lay-verify="uname" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">登录密码</label>
        <div class="col-xs-3">
          <input type="password" class="form-control" id="pass" placeholder="Password" required="" lay-verify="pass" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
		  <button type="button" id="login" class="btn btn-default" lay-filter="do_login" lay-submit="">登录</button>
        </div>
      </div>
    </form> 
  </div>
</div>

<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/jquery-1.12.4.js"></script>
<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript"> 
layui.use(['layer', 'form'], function(){
$ = layui.jquery;
var form = layui.form
,layer = layui.layer;

//自定义验证规则
form.verify({
	uname: function(value){
		if(value.length == ""){
			return '请输入你的登陆账户!';
		}
	}	
	,pass: function(value){
		if($('#pass').val()==""){
			return '登陆密码不能是空哦!';
		}
	}
});
  
//监听提交
form.on('submit(do_login)', function(data){
	var user = $("#user").val();
	var pass = $("#pass").val();
	var json = {submit:'login','user': user, 'pass': pass};
	$.post("member_login.php", json, function(data){
		if(data.result == -1){
			layer.msg(data.msg, {icon:2,time:1500});	
		}else if(data.result == -2){
			layer.msg(data.msg, {icon:2, time:1500});	
		}else if(data.result == -3){
			layer.msg(data.msg, {icon:2, time:1500});	
		}else{
			layer.msg(data.msg, {icon:6,time:3000});
			setTimeout(function(){window.location.href = 'member_show.php';},1500);				
		}
	});			
return false;
});
});
</script> 

</body>
</html>