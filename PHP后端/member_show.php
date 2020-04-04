<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/public/global.php';
//取站点配置信息
$web_data = get_web_config(1);//取站点配置信息
$web_config = unserialize($web_data['content']);//反序列化

url_allow();//设置允许远程跨域

//Ajax获取当前登录会员信息
if(isset($_GET['method']) && $_GET['method']=='get_member') {
	$db->ajax_get_member();//根据登录ID获取会员信息
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $web_config['web_name'];?> | 会员中心</title>
	<meta name="keywords" content="<?php echo $web_config['web_key'];?>" />
	<meta name="description" content="<?php echo $web_config['web_des'];?>" />		
    <!-- Bootstrap -->
    <link href="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/jquery-1.12.4.js"></script>
	<script src="<?php echo INDEX_STYLE;?>/bootstrap-3.3.7/js/bootstrap.min.js"></script>	
	<!-- layui插件 -->
	<script type="text/javascript" src="<?php echo INDEX_STYLE;?>/layui/layui.js"></script>
	<script type="text/javascript" src="<?php echo INDEX_STYLE;?>/layui/layui.all.js"></script>
	<script type="text/javascript">
		//Ajax获取当前登录会员信息
		$.get("member_show.php?method=get_member",  function(data){
			//alert(data);
			if(data.state == -1){
				layer.msg(data.msg, {icon:2,time:1500});
				setTimeout(function(){window.location.href = 'member_login.php';},1500);					
			}else if(data.state == -2){
				layer.msg(data.msg, {icon:2, time:1500});	
				setTimeout(function(){window.location.href = 'member_login.php';},1500);	
			}else if(data.state == -3){
				layer.msg(data.msg, {icon:2, time:1500});
				setTimeout(function(){window.location.href = 'member_login.php';},1500);					
			}else{
				$("#user").html(data.result.user);
				$("#os").html(data.result.reg_os);
				$("#date").html(data.result.reg_date);
			}
		});	
	</script>		
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

<div class="container">
<ul class="nav nav-pills">
	  <li role="presentation" class="active"><a href="member_show.php">会员中心</a></li>
	  <li role="presentation"><a href="member_mess.php">站内信息</a></li>
</ul>
  <div class="table-responsive">
    <table class="table table-striped">
    <thead>
      <tr>
       <td></td>
       <td></td>
      </tr>      
    </thead>      
    <thead>
      <tr>
       <td>登录用户</td>
       <td class="text-left" id="user"></td>
      </tr>        
    </thead>

    <thead>
      <tr>
       <td>注册设备</td>
       <td class="text-left" id="os"></td>
      </tr>        
    </thead>

    <thead>
      <tr>
       <td>注册日期</td>
       <td class="text-left" id="date"></td>
      </tr>        
    </thead>
    <thead>
      <tr>
       <td></td>
       <td></td>
      </tr>      
    </thead>   	
    </table>
  </div>
</div>
</body>
</html>
