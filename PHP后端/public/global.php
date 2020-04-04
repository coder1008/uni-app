<?php
/**
 +----------------------------------------------------
 * DES:全局公共文件
 * Date:2020-03-11
 * Author:冰封
 +----------------------------------------------------
*/
//定义常量
if(!defined("PUBLIC_PATH")) define('PUBLIC_PATH',  dirname(__FILE__) . DIRECTORY_SEPARATOR);		//公共文件路径
if(!defined("HASH_MD5")) define('HASH_MD5', 'Fz_9e@#!86q4Q');										//密码加密密匙
if(!defined("INDEX_STYLE")) define('INDEX_STYLE', './style');										//前台	

@session_start();
include_once PUBLIC_PATH . '../public/extends.php';          	//extends function	
include_once PUBLIC_PATH . '../public/function.php';          	//function
include_once PUBLIC_PATH . '../public/cls_mysql.class.php';   	//数据库类
include_once PUBLIC_PATH . '../public/admin_login.class.php'; 	//后台登录
include_once PUBLIC_PATH . '../public/admin_manager.class.php'; //后台操作
include_once PUBLIC_PATH . '../public/member_manager.class.php';//会员操作类
include_once PUBLIC_PATH . '../public/model.php';           	//数据处理
include_once PUBLIC_PATH . '../config.inc.php';           		//数据库配置


/**
 @实例化Mysql类
*/
  $db = new member_manager($db_host, $db_port, $db_user, $db_pass, $db_name, $db_charset);
 
/*
 @设置默认时区
*/
if(PHP_VERSION >= '5.1'){
	@date_default_timezone_set('Asia/Shanghai');
	//date('H:i:s',time() + 3600 * 8); 调整为东8区
}

/*
 @转义危险字符
*/
if(get_magic_quotes_gpc()){
	$_GET=mystripslashes($_GET);
	$_POST=mystripslashes($_POST);
	$_POST=mystripslashes($_REQUEST);
	$_COOKIE=mystripslashes($_COOKIE);
}
	
?>
