<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

$web_data = get_web_config('1');//取站点配置数据
$web_config_data = unserialize($web_data['content']);//反序列化

if(isset($_POST['sub']) && $_POST['sub']=="submit") {	
	$db->up_web_config();	//更新站点配置
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
          <cite>网站配置</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
		<blockquote class="layui-elem-quote"><i class="layui-icon x-show" status='true'>&#xe623;</i>全局配置信息</blockquote>
      </div>
	<form method="post" class="layui-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table class="layui-table">
        <thead>
        </thead>
        <tbody>
		
          <tr>
            <td width="72">站点域名:</td>
            <td width="423"><input type="text" name="web_url"  placeholder="站点域名" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_url'];?>"/></td>
            <td width="581">填写您站点的完整域名。例如: www.xxx.com，不要以斜杠 (“/”) 结尾</td>
          </tr>
          <tr>
            <td>站点名称:</td>
            <td><input type="text" name="web_title"  placeholder="站点名称" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_name'];?>"/></td>
            <td>网站名称:（应用范围：所有位置）</td>
          </tr>
          <tr>
            <td>Meta Keywords:</td>
            <td><input type="text" name="web_meta"  placeholder="Meta Keywords" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_des'];?>"/></td>
            <td>网站Meta Keywords标签有利用搜索引擎友好收录和SEO优化！</td>
          </tr>
          <tr>
            <td>Meta Description:</td>
            <td><input type="text" name="web_des"  placeholder="Meta Description" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_key'];?>"/></td>
            <td>网站Meta Description标签有利用搜索引擎友好收录！</td>
          </tr>
          <tr>
            <td>管理员邮箱:</td>
            <td><input type="text" name="web_admin"  placeholder="管理员邮箱" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_mail'];?>"/></td>
            <td>填写联系站点管理员的链接地址，可以是邮件(比如: mailto:admin@qq.net) 或其他链接</td>
          </tr>
          <tr>
            <td>ICP备案信息:</td>
            <td><input type="text" name="web_icp"  placeholder="ICP备案信息" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_icp'];?>"/></td>
            <td>填写ICP备案的信息，例如: 京ICP备xxxxxxxx号</td>
          </tr>
          <tr>
            <td>统计代码:</td>
            <td><input type="text" name="web_code"  placeholder="统计代码" autocomplete="off" class="layui-input" value="<?php echo $web_config_data['web_code'];?>"/></td>
            <td>请输入第三方网站流量统计代码</td>
          </tr>
          <tr>
            <td colspan="3"><button  class="layui-btn" lay-filter="add" lay-submit="">
                  更新配置
</button></td>
          </tr>
        </tbody>
      </table>
	  <input type="hidden" name="sub" value="submit"> 		
	</form>
    </div>
  </body>
</html>