<?php
/**
 +----------------------------------------------------
 * @DES:后台用户登陆
 * @Date:2020-03-11
 * @Author:冰封
 +----------------------------------------------------
*/

class admin_login extends cls_mysql{

	/**
	 * @用户登陆
	*/
	public function user_login($uname, $password, $code) {
		if($uname =='' || $password==""){
			show_msg('index.php','<font color=red>用户名或密码错误!</font>');
		}else if(md5($code) != $_SESSION['verify']){
			show_msg('index.php','<font color=red>验证码错误!</font>');
		}else{
			$result = manager_login($uname, md5($password . HASH_MD5));//取一条用户登陆的相关数据
			if($result==true){
				$this->set_SessionAndCookie($uname, $result['uid'], $uname, time());//设置Session或Cookie
				login_record($_SESSION['shell'], $result['uid'], $uname, get_client_ip(), time());//写入登录记录
				create_log_record($_SESSION['user'] . '用户于 '. date('Y-m-d H:i:s') .' 成功登入系统后台!');//写入日志
				show_msg('login.php','<font color=blue>登陆成功!</font>');
			}else{
				show_msg('index.php','<font color=red>用户名或密码错误!</font>');
				session_destroy();
			}
		}
	}

	/** 
	 * @设置Session或Cookie
	*/
	public function set_SessionAndCookie($Sequence_number, $uid, $user, $time, $type='session') {
	    $method = !empty($type) ? 'session' : 'cookies';
		switch($method){
			case 'session':
				$_SESSION['shell'] = md5($Sequence_number . $time);   
				$_SESSION['uid'] = $uid; 	
				$_SESSION['user'] = $user; 
				$_SESSION['time'] = $time; 				
				break;
			case 'cookies':
				/* cookie有效时间24小时 */ 
				setcookie('shell',md5($Sequence_number . $time),60*60*24);
				setcookie('uid', $uid, 60*60*24);
				setcookie('user', $user, 60*60*24);
				setcookie('time', $time, 60*60*24);
				break;
		}
	}

	
	/*
	 * @检查是SESSION或者COOKIE值是否存在
	*/
	public function check_login($type='session') {
		$method= !empty($type) ? 'session' : 'cookie';
		switch($method) {
			case 'session':
			if(!isset($_SESSION['shell']) && !isset($_SESSION['uid']) && !isset($_SESSION['user']) && !isset($_SESSION['time'])){
				show_msg("index.php","<font color=red>非法操作,请先登陆!</font>");
				exit();
			}
			break;
			case 'cookie':
			if(!isset($_COOKIE['shell']) && !isset($_COOKIE['uid']) && !isset($_COOKIE['user']) && !isset($_COOKIE['time'])){
				show_msg("index.php","<font color=red>非法操作,请先登陆!</font>");
				exit();
			}
			break;
		}
	}
	
	/**
	 * @用户登陆超时检查(秒)
	*/
	public function get_user_ontime($long = '1800') {
		$new_time = time();
		$onlinetime = $_SESSION['time'];
		if ($new_time - $onlinetime > $long) {
			show_msg('index.php','<font color=red>登录超时,请重新登录!</font>');
			unset($_SESSION);                 
			session_destroy();
			exit ();
		} else {
			$_SESSION['time'] = time();
		}
	}

	/** 
	 * @注销登陆{销毁SESSION}
	*/
	public function logout() {
		unset($_SESSION);   //删除SESSION
		session_destroy();  //终结SESSION会话
		unset($_COOKIE);    //注销COOKIE
		show_msg('index.php','<font color=blue>注销成功!</font>');
	}
}