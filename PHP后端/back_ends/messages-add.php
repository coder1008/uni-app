<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

//Ajax发布站内消息
if(isset($_POST['submit']) && $_POST['submit']=='submit') {
	$title = trim($_POST['title']);
	$conntent = trim($_POST['conntent']);
	$db->create_messages($title, $conntent);//发布站内消息
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
        <form class="layui-form">
          <!--<div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>栏目分类
              </label>
              <div class="layui-input-inline">
                  <select id="shipping" name="shipping" class="valid">
                    <option value="1">新闻资讯</option>
                  </select>
              </div>
          </div>-->
		  
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>消息标题
              </label>
              <div class="layui-input-inline">
				  <input type="text" id="title" name="title" placeholder="请输入标题"  required="" lay-verify="title"
                  autocomplete="off" class="layui-input">
              </div>
          </div>

          <div class="layui-form-item layui-form-text">
              <label for="desc" class="layui-form-label">
                  <span class="x-red">*</span>消息内容
              </label>
              <div class="layui-input-block">
                  <textarea placeholder="请输入内容" id="desc" name="desc" required="" lay-verify="desc"
                  autocomplete="off" class="layui-textarea"></textarea>
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="add" lay-submit="">
                  增加
              </button>
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
	title: function(value){
		if(value.length < 6){
			return '标题至少要填写啊!';
		}
	},
	desc: function(value){
		if(value.length < 6){
			return '消息内容至少也不能少于6个字吧!';
		}
	}
  });

	//监听提交
	form.on('submit(add)', function(data){
		console.log(data);
		var bt = $("#title").val();
		var nr = $("#desc").val();
		$.ajax({
		type:"POST",
		url:"messages-add.php",
		data:{submit:'submit',title:bt, conntent:nr},
		cache:false,
		success:function(d){
			var d=eval('('+d+')');
			if(d.flag){
				//发异步，把数据提交给php
				layer.alert(d.msg, {icon:6},function () {
					// 获得frame索引
					var index = parent.layer.getFrameIndex(window.name);
					//关闭当前frame
					parent.layer.close(index);	
					if(top.location!=parent.location){
						setTimeout(function(){parent.location.href = 'messages-list.php';});
					}	
				});				
			}else{
				layer.alert(d.msg,  {icon:2,time:1000});	
			}
		}
		});	
	return false;
	});
});
</script>
  </body>
</html>