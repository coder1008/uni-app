<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

//---搜索条件
$login_rerord_user = isset($_GET['username']) ? $_GET['username'] : '';//按用户名搜索

$counts =count_record_alls($login_rerord_user);//登录记录数据总数
pageft($counts, 20);
if ($firstcount < 0)
$firstcount = 0;
$record_data = login_record_alls($login_rerord_user, $firstcount, $displaypg);//取所有记录数据并分页

//删除操作
$method = isset($_GET['method']) ? $_GET['method'] : '';
switch($method) {
	case 'del_login_record':
		if(isset($_GET['del_id']) && $_GET['del_id']){	
			$del_id = $_GET['del_id'];
			$db->ajax_del_login_record($del_id);
		}
	break;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>后台管理-Fz's Admin 2.0</title>
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


  <body class="layui-anim layui-anim-up">
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>登陆统计</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
		 <form class="layui-form layui-col-md12 x-so" action="" name="form1" method="get">
          <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onClick="delAll()"><i class="layui-icon"></i>批量删除</button>

        <span class="x-right" style="line-height:40px">共有数据：<?php echo $counts; ?> 条</span>
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
            <th>登陆时间</th>
            <th>登录位置</th>
            <th>加入时间</th>
            <th>操作</th>
        </thead>
        <tbody>
<?php 
foreach($record_data as $k=>$v) { 
	$user_data = get_one_data($v['uid']);//根据UID取用户数据
?>		
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $v['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $v['id']; ?></td>
            <td><?php echo $v['user']; ?></td>
            <td><?php if($user_data['qid'] == '0') {echo '超级管理员';}else{echo '网站管理员';}?></td>
            <td><?php echo date('Y-m-d H:i:s' ,$v['login_time']); ?></td>
            <td><?php echo $v['ip'] .  convert_ip($v['ip']); ?></td>
            <td><?php echo date('Y-m-d H:i:s' ,$user_data['create_time']); ?></td>
            <td class="td-manage">
              <a title="删除" onClick="member_del(this,'<?php echo $v['id']; ?>')" href="javascript:;">
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
/*删除*/
function member_del(obj,id){
  layer.confirm('确认要删除吗？',function(index){
	$.ajax({
		url:'login_record_all.php',
		data:'method=del_login_record&del_id='+id,
		type:'GET',
		cache:false,
		success:function(d){	
			var d=eval('('+d+')');  
			if(d.flag){
				//发异步删除数据
				//$(obj).parents("tr").remove();
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
				url:'login_record_all.php',
				data:'method=del_login_record&del_id='+data,
				type:'GET',
				cache:false,
				success:function(d){	
					var d=eval('('+d+')');  
					if(d.flag){
					//捉到所有被选中的，发异步进行删除
						layer.msg(d.msg, {icon:1,time:1000});
						setTimeout(function(){window.location.href = '';},1000);	
						//$(".layui-form-checked").not('.header').parents('tr').remove();
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