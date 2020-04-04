<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/public/global.php';

//取站点配置信息
$web_data = get_web_config(1);//取站点配置信息
$web_config = unserialize($web_data['content']);//反序列化

url_allow();//设置允许远程跨域

//Ajax注册
if(isset($_POST['submit']) && $_POST['submit']=='sub') {
	$user = trim($_POST['user']);
	$pass = trim($_POST['pass']);
	$sex = $_POST['sex'];
	$db->create_member_data($user, $pass, $sex);//创建会员账户
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $web_config['web_name'];?> | 会员注册</title>
	<meta name="keywords" content="<?php echo $web_config['web_key'];?>" />
	<meta name="description" content="<?php echo $web_config['web_des'];?>" />		
    <!-- Bootstrap -->
    <link href="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/jquery-1.12.4.js"></script>
	<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/bootstrap.min.js"></script>
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
  <h2 class="sub-header">注册会员</h2>
    <form class="form-horizontal layui-form">
      <div class="form-group ">
        <label for="inputEmail3" class="col-sm-2 control-label">登录账户</label>
        <div class="col-xs-3">
          <input type="text" class="form-control layui-input" id="uname" placeholder="Email" required="" lay-verify="uname" autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">登录密码</label>
        <div class="col-xs-3">
          <input type="password" class="form-control" id="pass" placeholder="Password" required="" lay-verify="pass" autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">确认密码</label>
        <div class="col-xs-3">
          <input type="password" class="form-control" id="repass" placeholder="RePassword" required="" lay-verify="repass" autocomplete="off">
        </div>
      </div>
	  
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">性&ensp;&ensp;&ensp;&ensp;别</label>
        <div class="col-xs-3">
          <label class="radio-inline">
            <input type="radio" name="sex" value="1" checked>男生
          </label>
          <label class="radio-inline">
            <input type="radio" name="sex" value="0">女生
          </label>  
          </div>  
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="button" id="reg" class="btn btn-default" lay-filter="add" lay-submit="">注册</button>
        </div>
      </div>
    </form> 
  </div>
</div>

<script type="text/javascript"> 
layui.use(['layer', 'form'], function(){
$ = layui.jquery;
var form = layui.form
,layer = layui.layer;

//自定义验证规则
form.verify({
	uname: [/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/, "邮箱格式不正确哦!"]
	,pass: [/(.+){6,12}$/, '请输入6到12位的密码!']
	,repass: function(value){
		if($('#pass').val()!=$('#repass').val()){
			return '两次密码不一致哦!';
		}
	}
});
  
//监听提交
form.on('submit(add)', function(data){
	var user = $("#uname").val();
	var pass = $("#pass").val();
	var sex = $("input[type='radio']:checked").val();
	var json = {submit:'sub','user': user, 'pass': pass, 'sex': sex};
	$.post("member_reg.php", json, function(data){
		if(data.result == -1){
			layer.msg(data.msg, {icon:2,time:1500});	
		}else if(data.result == -2){
			layer.msg(data.msg, {icon:2, time:1500});	
		}else if(data.result == -3){
			layer.msg(data.msg, {icon:2, time:1500});	
		}else{
			layer.msg(data.msg, {icon:6,time:2000});	
			setTimeout(function(){window.location.href = 'member_show.php';},2000);	
		}
	});			
return false;
});
});
</script>  
</body>
</html>