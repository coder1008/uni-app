<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/global.php';
$db->check_login();//检查用户是否登录
$db->get_user_ontime();//检查登录是否超时


$get_data = get_one_data($_SESSION['uid']);//根据uid取一条当前登录数据
$login_data_now = login_time_now($_SESSION['shell'], $_SESSION['uid']);//取当前登录记录
$pre_login_data = pre_login_data($_SESSION['shell'], $_SESSION['uid']);//取上次登陆记录

$member_nums = get_member_count();//取会员总数
$manager_nums = get_login_nums();// 取管理总数
$messages_nums = get_messages_count();// 取站内消息总数
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
</head>
    <body>
    <div class="x-body layui-anim layui-anim-up">
        <blockquote class="layui-elem-quote">欢迎超级管理员：
            <span class="x-red"><?php echo $_SESSION['user'];?></span>  当前时间:<?php echo date('Y-m-d H:i:s');?></blockquote>
        <fieldset class="layui-elem-field">
            <legend>数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body" title="视频总数">
                                                <h3>会员总数</h3>
                                                <p>
                                                    <cite><?php echo $member_nums;?></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body" title="图片总数">
                                                <h3>消息总数</h3>
                                                <p>
                                                    <cite><?php echo $messages_nums;?></cite></p>
                                            </a>
                                        </li>										
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body" title="图片总数">
                                                <h3>管理总数</h3>
                                                <p>
                                                    <cite><?php echo $manager_nums;?></cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>基本信息</legend>
            <div class="layui-field-box">
                 <table class="layui-table">
                    <tbody>
                        <tr>
                             <th width="235" >
                                用户级别：
                            </th>
                            <td><?php if($get_data['qid']==="0"){ echo '超级管理员';}else{ echo '普通管理员';}?></td>
                        </tr>
                        <tr>
                            <th>
                               登陆用户：
                            </th>
                            <td ><?php echo $_SESSION['user'];?></td>
                        </tr>
                        <tr>
                            <th>
                               登陆位置：
                            </th>
                            <td ><?php echo $login_data_now['ip'] . convert_ip($login_data_now['ip']); ?></td>
                        </tr>						
                        <tr>
                            <th>
                               登陆时间：
                            </th>
                            <td ><?php echo date('Y-m-d H:i:s',$login_data_now['login_time']); ?></td>
                        </tr>						
                        <tr>
                            <th>
                               上次位置：
                            </th>
                            <td ><?php echo $pre_login_data['ip'] . convert_ip($pre_login_data['ip']); ?></td>
                        </tr>	
                        <tr>
                            <th>
                              上次登陆：
                            </th>
                            <td ><?php echo date('Y-m-d H:i:s',$pre_login_data['login_time']); ?></td>
                        </tr>							
                    </tbody>
                </table>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>系统信息</legend>
            <div class="layui-field-box">
                <table class="layui-table">
                    <tbody>
                        <tr>                         
                            <th width="235" >服务器IP地址</th>
                            <td><?php
										function get_server_ip(){
											if(!empty($_SERVER['SERVER_ADDR'])){
												return $_SERVER['SERVER_ADDR'];
											}else{
												return gethostbyname($_SERVER['HOSTNAME']);
											}
										}

										echo get_server_ip();
								?></td></tr>
                        <tr>                         
                            <th width="235" >服务器域名</th>
                            <td><?php echo $_SERVER['HTTP_HOST']; ?></td></tr>	
                            <th width="235" >服务器端口</th>
                            <td><?php echo $_SERVER["SERVER_PORT"];?></td></tr>								
                        <tr>
                            <th>操作系统</th>
                            <td><?php echo @PHP_OS;?></td></tr>
                        <tr>
                            <th>运行环境</th>
                            <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
                        <tr>
                            <th>PHP版本</th>
                            <td><?php echo PHP_VERSION;?></td></tr>
                        <tr>
                            <th>PHP运行方式</th>
                            <td><?php echo @php_sapi_name(); ?></td></tr>								
                        <tr>
                            <th>MYSQL版本</th>
                            <td><?php echo $db->mysql_server();?></td></tr>
                        <tr>
                            <th>GD版本</th>
                            <td>
								<?php  
								$gd=@gd_info();
								if(jz_is_array($gd)){
									echo preg_replace("/[^0-9\.]/i","",$gd['GD Version']);
								}else{
									echo '检查失败';
								} ?>
							</td></tr>
                        <tr>
                            <th>上传附件限制</th>
                            <td><?php echo get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许上传附件"; ?></td></tr>
                        <tr>
                            <th>执行时间限制</th>
                            <td><?php echo get_cfg_var("max_execution_time");?>s</td></tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>版权信息</legend>
            <div class="layui-field-box">
                <table class="layui-table">
                    <tbody>
                        <tr>
                            <th><a class="x-a" href="javascript:void(0)" >程序开发：</a></th>
                            <td>冰封</td>
                        </tr>
                        <tr>
                            <th><a class="x-a" href="javascript:void(0)" >程序版权：</a></th>
                            <td>Fz's Free [574578944@qq.com]</td></tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery。</blockquote>
    </div>
    </body>
</html>