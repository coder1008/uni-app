<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时


//搜索起始时间与结束时间
// $bef_time = strtotime('-1 days', strtotime(date('Y-m-d') . ' 23:59:59')); //前一天00时
// $start = !empty($_GET['start']) ? strtotime($_GET['start']) : $bef_time;
// $end= !empty($_GET['end']) ? strtotime($_GET['end']  . ' 23:59:59') : time();

$start = !empty($_GET['start']) ? strtotime($_GET['start']) : '';
$end= !empty($_GET['end']) ? strtotime($_GET['end']  . ' 23:59:59') : time();


//---搜索条件
$condition_os = !empty($_GET['contrller']) ? $_GET['contrller'] : '';//按注册设备类型搜索
$condition_user = !empty($_GET['member']) ? $_GET['member'] : '';//按会员名搜索

$member_nums = get_member_nums($start, $end, $condition_os, $condition_user);//会员数据总条数
pageft($member_nums, 20);
if ($firstcount < 0)
	$firstcount = 0;
$member_data = get_all_member($start, $end, $condition_os, $condition_user, $firstcount, $displaypg);//取所有会员数据并分页


//删除操作
$method = isset($_GET['method']) ? $_GET['method'] : '';
switch($method) {
	case 'ajax_del_member'://删除操作
		if(isset($_GET['del_id']) && $_GET['del_id']) {
			$id = (int)$_GET['del_id'];
			$db->delete_member_data($id); //根据UID删除登录账户
		}
	break;
	
	case 'ajax_up_state'://启用于停用会员账户操作
	if(isset($_GET['up_id']) && $_GET['up_id']) {
		$id = intval($_GET['up_id']);		
		$state = intval($_GET['state']);
		$db->up_member_state($id, $state); //根据UID冻结以及解锁会员账户
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
        <a href="">会员管理</a>
        <a>
          <cite>会员列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
          <input class="layui-input" placeholder="开始日" name="start" id="start">
          <input class="layui-input" placeholder="截止日" name="end" id="end">
          <div class="layui-input-inline">
            <select name="contrller">
				<option value="">全部类型</option>
				<option value="windwos">windwos</option>
				<option value="iphone">iphone</option>
				<option value="android">android</option>
				<option value="ipad">ipad</option>				
				<option value="mac os">mac os</option>
				<option value="ipod">ipod</option>
				<option value="unix">unix</option>
				<option value="linux">linux</option>
				<option value="other">other</option>			      
            </select>
          </div>		  
          <input type="text" name="member"  placeholder="请输入会员账号" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：<?php echo $member_nums ;?> 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>用户名</th>
            <th>性别</th>
            <th>设备</th>
			<th>状态</th>
            <th>加入时间</th>
            <th >操作</th>
			</tr>
        </thead>
        <tbody>
		<?php foreach($member_data as $key=>$val) {	?>				
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $val['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $val['user']; ?></td>
            <td><?php if($val['sex'] == 1){echo '男';}else{echo '女';} ?></td>
            <td><?php echo $val['reg_os']; ?></td>	
            <td class="td-status">
			<?php 
				if($val['unlock'] == '0') {
					echo '<span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>';
				}else{
					echo '<span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>';
				}
			?> 
			</td>			
            <td><?php echo date('Y-m-d H:i:s' ,$val['reg_date']); ?></td>
	
            <td class="td-manage">
			<?php if($val['unlock'] == '0') { ?>
					<a onClick="member_stop(this,'<?php echo $val['uid'];?>')" href="javascript:;"  title="启用"><i class="layui-icon">&#xe62f;</i></a>
			<?php }else{ ?>
					<a onClick="member_stop(this,'<?php echo $val['uid'];?>')" href="javascript:;"  title="停用"><i class="layui-icon">&#xe601;</i></a>
			<?php } ?> 
              <!--<a title="编辑"  onclick="x_admin_show('编辑','member-edit.php?mid=<?php echo $val['uid'];?>')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>-->	  
              <a title="删除" onClick="member_del(this,'<?php echo $val['uid']; ?>')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
          </tr>
		<?php } ?>
        </tbody>
      </table>
      <div class="page"><?php echo $pagenav; ?> </div>
    </div>

<script>
layui.use('laydate', function(){
	var laydate = layui.laydate;
	//执行一个laydate实例
	laydate.render({
	  elem: '#start' //指定元素
	});

	//执行一个laydate实例
	laydate.render({
	  elem: '#end' //指定元素
	});
});


/*用户-停用*/
function member_stop(obj,id){
  layer.confirm('你确认要停用这样做吗？',function(index){
	  if($(obj).attr('title')=='启用'){
		$.ajax({
			type:"GET",
			url:"member-list.php",
			data:'method=ajax_up_state&up_id='+id + '&state=1',
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
			type:"GET",
			url:"member-list.php",
			data:'method=ajax_up_state&up_id=' + id + '&state=0',
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
		url:'member-list.php',
		data:'method=ajax_del_member&del_id='+id,
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

//批量删除
function delAll (argument) {
	var data = tableCheck.getData();
	if(data==false){
		layer.msg('请勾选您要操作的选项', {icon:2,time:1000});		
	}else{
		layer.confirm('确认要删除吗?',function(index){
			$.ajax({
				url:'member-list.php',
				data:'method=ajax_del_member&del_id='+data,
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