<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

//---搜索条件
$pay_numbers = isset($_GET['number']) ? $_GET['number'] : '';//按二维码编号搜索

$count_nums = count_pay($pay_numbers);//总数据条数
pageft($count_nums, 20);
if ($firstcount < 0)
	$firstcount = 0;
$pay_data = get_pay_all($pay_numbers, $firstcount, $displaypg);//取所所有后台账户并分页


//删除操作
$method = isset($_GET['method']) ? $_GET['method'] : '';
switch($method) {
	case 'ajax_del':
		if(isset($_GET['del_id']) && $_GET['del_id']){	
			$id = $_GET['del_id'];
			$db->ajax_del_pay_data($id);
		}
	break;	
}

//更新状态
if(isset($_POST['submit']) && $_POST['submit']=='up_state') {
	$pid = (int)$_POST['up_id'];
	$state = (int)$_POST['state'];
	$db->ajax_update_pay($pid, $state);
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
        <a href="">系统设置</a>
        <a>
          <cite>支付设置</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
		 <form class="layui-form layui-col-md12 x-so" action="" name="form1" method="get">
          <input type="text" name="number"  placeholder="二维码编号" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onClick="delAll()"><i class="layui-icon"></i>删除</button>
        <button class="layui-btn" onClick="x_admin_show('添加收款信息','./pay_setting-add.php')"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：<?php echo $count_nums;?> 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>二维码编号</th>
            <th>增加时间</th>
            <th>状态</th>
            <th>操作</th>
        </thead>
        <tbody>
<?php foreach($pay_data as $key=>$val) { ?>		
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $val['pid'];?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $val['pid'];?></td>
            <td><?php echo $val['odd_number'];?></td>
            <td><?php echo date('Y-m-d H:i:s' ,$val['create_time']); ?></td>
            <td class="td-status">
			<?php 
				if($val['state'] == '0') {
					echo '<span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>';
				}else{
					echo '<span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>';
				}
			?> 
			  </td>
            <td class="td-manage">
			<?php if($val['state'] == '0') { ?>
					<a onClick="member_stop(this,'<?php echo $val['pid'];?>')" href="javascript:;"  title="启用"><i class="layui-icon">&#xe62f;</i></a>
			<?php }else{ ?>
					<a onClick="member_stop(this,'<?php echo $val['pid'];?>')" href="javascript:;"  title="停用"><i class="layui-icon">&#xe601;</i></a>
			<?php } ?> 			
              <!--<a title="编辑"  onclick="x_admin_show('编辑','admin-edit.html')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>-->
              <a title="删除" onClick="member_del(this,'<?php echo $val['pid'];?>')" href="javascript:;">
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
/*用户-停用*/
function member_stop(obj,id){
  layer.confirm('你确认要这样做吗？',function(index){
	  if($(obj).attr('title')=='启用'){
		$.ajax({
			type:"POST",
			url:"pay-setting.php",
			data:{submit:'up_state',up_id:id,state:1},
			cache:false,
			success:function(d){
				var d=eval('('+d+')');
				if(d.flag){
					//发异步把用户状态进行更改
					$(obj).attr('title','停用')
					$(obj).find('i').html('&#xe601;');
					$(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
					layer.msg('已启用!',{icon: 6,time:1000});					
				}else{
					layer.alert(d.msg,  {icon:2,time:1000});	
				}
			}
		});
	  }else{
		$.ajax({
			type:"POST",
			url:"pay-setting.php",
			data:{submit:'up_state',up_id:id,state:0},
			cache:false,
			success:function(d){
				var d=eval('('+d+')');
				if(d.flag){
					$(obj).attr('title','启用')
					$(obj).find('i').html('&#xe62f;');
					$(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
					layer.msg('已停用!',{icon: 5,time:1000});					
				}else{
					layer.alert(d.msg,  {icon:2,time:1000});	
				}
			}
		});			  
	  }
	  
  });
}

 /*删除*/
function member_del(obj,id){
  layer.confirm('确认要删除吗？',function(index){
	$.ajax({
		url:'pay-setting.php',
		data:'method=ajax_del&del_id='+id,
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

//批量删除
function delAll(argument) {
	var data = tableCheck.getData();
	if(data==false){
		layer.msg('请勾选您要操作的选项', {icon:2,time:1000});		
	}else{
		layer.confirm('确认要删除吗?',function(index){
			$.ajax({
				url:'pay-setting.php',
				data:'method=ajax_del&del_id='+data,
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