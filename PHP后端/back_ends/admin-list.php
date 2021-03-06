<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

$count_num = get_login_nums();//总数据条数
pageft($count_num, 20);
if ($firstcount < 0)
	$firstcount = 0;
$login_data = get_all_login($firstcount, $displaypg);//取所所有后台账户并分页


//删除操作
$method = isset($_GET['method']) ? $_GET['method'] : '';
switch($method) {
	case 'del_login'://删除操作
		if(isset($_GET['del_id']) && $_GET['del_id']) {
			$uid = $_GET['del_id'];
			$db->del_login_data($uid); //根据UID删除登录账户
		}
	break;
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
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>用户列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <xblock>
        <button class="layui-btn layui-btn-danger" onClick="delAll()"><i class="layui-icon"></i>删除</button>
        <button class="layui-btn" onClick="x_admin_show('添加用户','admin-add.php')"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：<?php echo $count_num; ?> 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>登录名</th>
            <th>角色</th>
            <th>加入时间</th>
            <th>登陆统计</th>
            <th>操作</th>
        </thead>
        <tbody>
		<?php foreach($login_data as $key=>$val) { ?>
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $val['uid']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $val['uid']; ?></td>
            <td><?php echo $val['user']; ?></td>
            <td><?php if($val['qid'] == '0') { echo '超级管理员';}else{echo '网站管理员';} ?></td>
            <td><?php echo date('Y-m-d H:i:s' ,$val['create_time']); ?></td>
            <td><a title="查看登录记录"  onclick="x_admin_show('<?php echo $val['user'];?> 用户的登录记录','login_record_list.php?uid=<?php echo $val['uid'];?>')" href="javascript:;" class="layui-btn layui-btn-normal layui-btn-mini">登录统计</a></td>
            <td class="td-manage">
              <a title="编辑"  onclick="x_admin_show('编辑用户','admin-edit.php?uid=<?php echo $val['uid'];?>')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
              <a title="删除" onClick="member_del(this,'<?php echo $val['uid']; ?>')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
          </tr>
		<?php } ?>
        </tbody>
      </table>
      <div class="page"><?php echo $pagenav; ?></div>

    </div>
<script>
/*用户-删除*/
function member_del(obj,id){
  layer.confirm('确认要删除吗？',function(index){
	$.ajax({
		url:'admin-list.php',
		data:'method=del_login&del_id='+id,
		type:'GET',
		cache:false,
		success:function(d){	
			var d=eval('('+d+')');  
			if(d.flag){
				//发异步删除数据
				layer.msg(d.msg, {icon:1,time:1000});
				setTimeout(function(){window.location.href = '';},1000);				
			}else {
				layer.msg(d.msg, {icon:2,time:1000});	
			}
		}
	});		 
  });
}


function delAll (argument) {
	var data = tableCheck.getData();
	if(data==false){
		layer.msg('请勾选您要操作的选项', {icon:2,time:1000});		
	}else{	
		layer.confirm('确认要删除吗?',function(index){
			$.ajax({
				url:'admin-list.php',
				data:'method=del_login&del_id='+data,
				type:'GET',
				cache:false,
				success:function(d){	
					var d=eval('('+d+')');  
					if(d.flag){
						//捉到所有被选中的，发异步进行删除
						layer.msg(d.msg, {icon: 1});
						setTimeout(function(){window.location.href = '';},1000);
					}else {
						layer.msg(d.msg, {icon:2,time:1000});	
					}
				}
			});	
		});
	}
}
</script>
 </body>
</html>