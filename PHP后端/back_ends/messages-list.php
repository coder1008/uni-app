<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

$start = !empty($_GET['start']) ? strtotime($_GET['start']) : '';
$end= !empty($_GET['end']) ? strtotime($_GET['end']  . ' 23:59:59') : time();

//---搜索条件
$condition = !empty($_GET['title']) ? $_GET['title'] : '';//按站内消息标题搜索

$messages_nums = get_messages_nums($start, $end, $condition);//站内消息数据总条数
pageft($messages_nums, 20);
if ($firstcount < 0)
	$firstcount = 0;
$messages_data = get_all_messages($start, $end, $condition, $firstcount, $displaypg);//取所有站内消息数据并分页


//Ajax删除站内消息数据
$method = isset($_GET['method']) ? $_GET['method'] : '';
switch($method) {
	case 'del_mess'://删除操作
		if(isset($_GET['del_id']) && $_GET['del_id']) {
			$id = $_GET['del_id'];
			$db->del_mess_data($id); //根据ID删除站内消息数据
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
          <input type="text" name="title"  placeholder="请输入标题" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
         <button class="layui-btn" onclick="x_admin_show('发布消息','./messages-add.php')"><i class="layui-icon"></i>发布消息</button>       
		<span class="x-right" style="line-height:40px">共有数据：<?php echo $messages_nums ;?> 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>消息标题</th>
            <th>发布日期</th>
            <th >操作</th>
			</tr>
        </thead>
        <tbody>
		<?php foreach($messages_data as $key=>$val) {	?>				
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $val['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $val['id']; ?></td>
            <td><?php echo $val['title']; ?></td>
            <td><?php echo date('Y-m-d H:i:s' ,$val['add_time']); ?></td>
            <td class="td-manage">
              <a title="查看"  onclick="x_admin_show('编辑','messages-view.php?mess_id=<?php echo $val['id']; ?>')" href="javascript:;">
                <i class="layui-icon">&#xe63c;</i>
              </a>
              <a title="删除" onclick="member_del(this,'<?php echo $val['id']; ?>')" href="javascript:;">
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

/*用户-删除*/
function member_del(obj,id){
  layer.confirm('确认要删除吗？',function(index){
	$.ajax({
		url:'messages-list.php',
		data:'method=del_mess&del_id='+id,
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
				url:'messages-list.php',
				data:'method=del_mess&del_id='+data,
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