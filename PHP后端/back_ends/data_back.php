<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/databackup.class.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时


$data_back = new databackup($db_host, $db_port, $db_name, $db_user, $db_pass);
$backup_list= $data_back->index();


//备份 删除 下载 还原操作
$method = isset($_GET['method']) ? $_GET['method'] : '';
$qid = $db->check_power();//取当前登录权限ID
switch($method) {
	case 'backup':
	if($method =='backup') {
		if($qid =='0') {
			$data_back->backup();
		}else{
			show_msg('data_back.php', '<font color="red">Soory,你无此操作权限!</font>');
		}
	}
	break;	
	
	case 'del':
	if(isset($_GET['file']) && $_GET['file']){
		if($qid =='0') {
				$data_back->deleteBak($_GET['file']);
		}else{
			show_msg('data_back.php', '<font color="red">Soory,你无此操作权限!</font>');
		}
	}
	break;
	
	case 'downfile':
	if(isset($_GET['file']) && $_GET['file']){
		if($qid =='0') {
			$data_back->downloadBak($_GET['file']);
		}else{
			show_msg('data_back.php', '<font color="red">Soory,你无此操作权限!</font>');
		}
	}	
	break;
	
	case 'recover':
	if(isset($_GET['file']) && $_GET['file']){
		if($qid =='0') {
			$data_back->recover($_GET['file']);
		}else{
			show_msg('data_back.php', '<font color="red">Soory,你无此操作权限!</font>');
		}
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
<script type="text/javascript">
<!--	
function click_back(){
	window.location.href='?method=backup';
}

//下载
function down_back(file_name){
	window.location.href='?method=downfile&file='+file_name;
}

/*删除*/
function back_del(file_name){
  layer.confirm('确认要删除吗?',function(index){
	  window.location.href='?method=del&file='+file_name;
  });
}

/*还原*/
function recover_file(file_name){
  layer.confirm('确定要还原吗?',function(index){
	  window.location.href='?method=recover&file='+file_name;
  });
}


//批量删除操作
function delAll(argument) {
	var data = tableCheck.getData();
	if(data==false){
		layer.msg('请勾选您要操作的选项', {icon:2,time:1000});		
	}else{
		//alert(data);
		layer.confirm('您真的要删除吗？',function(index){
			window.location.href='?method=del&file='+data;
		});			
	}
}
//-->
</script>
</head>

  <body>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">系统管理</a>
        <a>
          <cite>数据备份</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
		<blockquote class="layui-elem-quote"><i class="layui-icon x-show" status='true'>&#xe623;</i>注意:点击备份可将所有的数据表结构和数据完全备份到/uploads_data/backup_data/目录下(Linux下注意将/uploads_data/目录权限设置为777)</blockquote>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onClick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onClick="click_back()"><i class="layui-icon"></i>一键备份</button>
        <span class="x-right" style="line-height:40px">共有数据：<?php echo count($backup_list); ?>条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>备份记录</th>
            <th>文件大小</th>
            <th>备份日期</th>
            <th>操作</th>
        </thead>
        <tbody>
		<?php 
		$i=0;
		foreach($backup_list as $data_list) { 
		$i++;
		?>
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $data_list['name']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $i; ?></td>
            <td><?php echo $data_list['name']; ?></td>
            <td><?php echo $data_list['size']; ?></td>
            <td><?php echo $data_list['time']; ?></td>
            <td class="td-manage">
              <a onClick="down_back('<?php echo $data_list['name'];?>')" href="javascript:;"  title="下载">
                <i class="layui-icon">&#xe601;</i>
              </a>
              <a title="删除" onClick="back_del('<?php echo $data_list['name'];?>')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
			  <a title="还原" onClick="recover_file('<?php echo $data_list['name'];?>')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
            </td>
          </tr>
		  <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>