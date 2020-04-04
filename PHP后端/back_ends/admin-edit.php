<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
if($uid){
	$login_data = get_one_data($uid); 	//根据UID取一条数据
	if(intval($login_data['qid']) == 0) {//审核的状态
		$qx_0='checked="checked"';
		$qx_1='';
	}else{
		$qx_0='';
		$qx_1='checked="checked"';	
	}	
}
				

//修改密码
if(isset($_POST['submit']) && $_POST['submit']=='update') {
	$uid = trim($_POST['uid']);					//UID
	$old_pass = trim($_POST['old_pass']);		//原始密码
	$new_pass = trim($_POST['passwd']);			//新密码
	$zt = intval($_POST['zt']);					//权限级别
	$db->edit_login_pass($uid, $old_pass, $new_pass, $zt);//更新密码操作
}
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
        <form class="layui-form">
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>登录账户
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="username" name="username" required="" lay-verify="required"
                  autocomplete="off" value="<?php echo $login_data['user']; ?>" class="layui-input" readonly="readonly">
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label"><span class="x-red">*</span>角色权限</label>
              <div class="layui-input-block">
                <input type="radio" name="check" lay-skin="primary" title="超级管理" value="0" lay-verify="primary" <?php echo $qx_0; ?>>
                <input type="radio" name="check" lay-skin="primary" title="网站管理" value="1" lay-verify="primary" <?php echo $qx_1; ?>>
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_email" class="layui-form-label">
                  <span class="x-red">*</span>原始密码
              </label>
              <div class="layui-input-inline">
                  <input type="text" value="" id="old_pass" name="old_pass" required="" lay-verify="old_pass"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span> 请输入原始的6-12个字符或数字的登录密码！
              </div>
          </div>		  
          <div class="layui-form-item">
              <label for="L_pass" class="layui-form-label">
                  <span class="x-red">*</span>新的密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_pass" name="pass" required="" lay-verify="pass"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span> 请使用自己容易记住的密码，6－12个字符或数字!
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
                  <span class="x-red">*</span>确认密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_repass" name="repass" required="" lay-verify="repass"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span> 重复确认登录密码，必须和上面的密码一致，6－12个字符或数字!
              </div>			  
          </div>		  
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="edit" lay-submit="">
                  修改
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
		old_pass: function(value){
			if(value.length < 6 || value.length >= 12 ){
				return '请输入6到12位原始密码！';
			}
			
			
		}
		,pass: [/(.+){6,12}$/, '密码必须6到12位']
		,repass: function(value){
			if($('#L_pass').val()!=$('#L_repass').val()){
				return '两次密码不一致';
			}
		}
	});

	//监听提交
	form.on('submit(edit)', function(data){
		console.log(data);
		var old_pass = $("#old_pass").val();
		var zt = $('input:radio:checked').val();
		var pass = $("#L_pass").val();	
		$.ajax({
		type:"POST",
		url:"admin-edit.php",
		data:{submit:'update',uid:<?php echo $login_data['uid'];?>, old_pass:old_pass, passwd:pass, zt:zt},
		cache:false,
		success:function(d){
			var d=eval('('+d+')');
			if(d.flag){
				//发异步，把数据提交给php
				layer.alert(d.msg, {icon:6},function () {
					if(top.location!=parent.location){
						setTimeout(function(){parent.location.href = 'admin-list.php';});
					}					
					// 获得frame索引
					var index = parent.layer.getFrameIndex(window.name);
					//关闭当前frame
					parent.layer.close(index);	
					
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