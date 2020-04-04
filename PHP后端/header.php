<?php 
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/public/global.php';
//注销当前登录
if(isset($_GET['method']) && $_GET['method'] =='login_out') { 
	$db->member_logout();	 
}
?>

<?php if(isset($_SESSION['mid'])) { ?>
<ul class="nav navbar-nav navbar-right">
	<li><a href="member_show.php"><?php echo $_SESSION['member']; ?></a></li>
	<li><a href="?method=login_out">注销</a></li>
	</ul>
<?php }else{ ?>
<ul class="nav navbar-nav navbar-right">
	<li><a href="member_reg.php">注册</a></li>
	<li><a href="member_login.php">登录</a></li>
	</ul>
<?php } ?>