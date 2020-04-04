<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/public/global.php';
//取站点配置信息
$web_data = get_web_config(1);//取站点配置信息
$web_config = unserialize($web_data['content']);//反序列化

url_allow();//设置允许远程跨域

//Ajax获取站内消息
if(isset($_GET['method']) && $_GET['method']=='get_messages') {
	$db->ajax_get_messages();//获取所有站内消息数据
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
		$.get("member_mess.php?method=get_messages",  function(data){

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
				for(var i =0; i < data.result.length; i++){
					//alert(data.result[i].title);
					str="<thead>" + "<tr>";
					str+="<td><a href=member_mess_detail.php?mess_id=" + data.result[i].id + " >";
					str+=data.result[i].title + "</a></td>";
					str+="<td class=\"text-right\" id=\"title\">发布日期:" + data.result[i].date + "</td>";
					str+="</tr>" + "</thead>";
					$("#moban").after(str);//DOM操作,在被选元素之后插入内容	
				}	
				$("#counts").append(data.result.length);//数据总数			
				
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
	  <li role="presentation"><a href="member_show.php">会员中心</a></li>
	  <li role="presentation" class="active"><a href="member_mess.php">站内信息</a></li>
	</ul>
   <div class="table-responsive">
    <table class="table table-striped">
    <thead id="moban">
      <tr>
       <td></td>
       <td></td>
      </tr>      
    </thead> 
	

    <!--<thead>
      <tr>
       <td>哈利波特高清免费观看地址</td>
       <td class="text-right" id="title">发布日期:2020-03-8</td>
      </tr>        
    </thead>-->


   <thead>
       <tr>
       <td class="text-left"><!--<a href="#">首页</a>&ensp;&ensp;<a href="#">上一页</a>&ensp;&ensp;<a href="#">下一页</a>&ensp;&ensp;<a href="#">尾页</a>&ensp;&ensp;-->共<span id="counts"></span>条记录</td>
      <td></td> 
      </tr>    
    </thead>
    </table>
  </div>

</div>
</body>
</html>
