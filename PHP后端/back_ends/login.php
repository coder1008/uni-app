<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时

//注销当前登录
if(isset($_GET['method']) && $_GET['method'] =='logout') { 
	$db->logout();	 
}
?> 
<!doctype html>
<html lang="en">
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
	
<script type="text/javascript"> 
<!--
function show_url(urls){
	$('iframe').attr('src', urls);  	
}
//-->
</script>
</head>
<body>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href="./index.html">Fz's Admin 2.0</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav left fast-add" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">+快速增加</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->	  
               <dd><a onclick="x_admin_show('增加管理','admin-add.php')"><i class="iconfont">&#xe6b8;</i>系统用户</a></dd>
               <dd><a onclick="x_admin_show('发布公告','messages-add.php')"><i class="iconfont">&#xe6b8;</i>站内消息</a></dd>			   
            </dl>
          </li>
        </ul>
        <ul class="layui-nav right" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;"><?php echo $_SESSION['user']; ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a onclick="x_admin_show('修改密码','admin-edit.php?uid=<?php echo $_SESSION['uid'];?>')">修改密码</a></dd>
              <dd><a onclick="x_admin_show('<?php echo $_SESSION['user']; ?> 用户的登录记录','login_record_list.php?uid=<?php echo $_SESSION['uid']; ?>')">登录统计</a></dd>
              <dd><a href="?method=logout">注销登录</a></dd>
            </dl>
          </li>
          <li class="layui-nav-item to-index"><a href="/" target ="_blank">前台首页</a></li>
        </ul> 
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
    <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
		            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6fc;</i>
                    <cite>系统设置</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="web_config.php">
                            <i class="iconfont">&#xe696;</i>
                            <cite>网站配置</cite>
                        </a>
                    </li >								
                </ul>
            </li>
		
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="member-list.php">
                            <i class="iconfont">&#xe6b8;</i>
                            <cite>会员列表</cite>
                            
                        </a>
                    </li >
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6a2;</i>
                    <cite>内容管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="messages-list.php">
                            <i class="iconfont">&#xe6a2;</i>
                            <cite>站内消息</cite>  
                        </a>
                    </li >				
                </ul>
            </li>

            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>管理员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="admin-list.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>用户列表</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="login_record_all.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>登录统计</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>系统管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="data_back.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>数据备份</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="log_record.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>操作日志</cite>
                        </a>
                    </li>	
                    <li>
                        <a _href="check_env.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>系统环境</cite>
                        </a>
                    </li>						
                </ul>
            </li>			
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b4;</i>
                    <cite>图标字体</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="unicode.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>图标对应字体</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
      </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
          </ul>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='main.php' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">Copyright ©2020 Fz's Admin-v2.0 All Rights Reserved</div>  
    </div>
    <!-- 底部结束 -->
</body>
</html>