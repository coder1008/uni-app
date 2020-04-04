<?php
/**
 +----------------------------------------------------
 * @DES:会员相关操作
 * @Date:2020-03-12
 * @Author:冰封
 +----------------------------------------------------
*/

class member_manager extends admin_manager{
	
	/** 
	 * @设置Session或Cookie
	*/
	public function set_member_session($seq_number, $uid, $user, $time, $type='session') {
	    $method = !empty($type) ? 'session' : 'cookie';
		switch($method){
			case 'session':
				$_SESSION['seq'] = md5($seq_number . $time);   
				$_SESSION['mid'] = $uid; 	
				$_SESSION['member'] = $user; 
				$_SESSION['time'] = $time; 				
				break;
			case 'cookies':
				/* cookie有效时间24小时 */ 
				setcookie('seq',md5($seq_number . $time),60*60*24);
				setcookie('mid', $uid, 60*60*24);
				setcookie('member', $user, 60*60*24);
				setcookie('time', $time, 60*60*24);
				break;
		}
	}
	
	/**
	 * ajax 注册会员
	*/
	public function create_member_data($user, $pass, $sex){
			header('Content-Type:application/json');//设置返回header头为json的类型	
			if($user =='' || $pass==""){
				echo '{"msg":"用户名或密码不能为空!","result":"-1"}';
			}else{
				$check_user = check_member($user);//检查会员账号是否被占用			
				if($check_user){
					echo '{"msg":"此会员账户已被使用,请更换其他帐户!","result":"-2"}';
				}else{
					$os = get_client_os();//取客户端OS类型
					//创建系统账户，并返回插入的数据ID
					$insert_id = create_member_data($user, md5($pass . HASH_MD5), $sex, $os, time());
					if($insert_id != 0) {
						$this->set_member_session($user, $insert_id, $user, time());//注册成功设置SESSION
						echo '{"msg":"注册成功!","result":"0"}';
					}else{
						echo '{"msg":"注册失败!","result":"-3"}';
					}					
				}				
			}		
		die();
	}

	/**
	 * Ajax 会员登陆
	*/
	public function member_dologin($member, $password) {
		header('Content-Type:application/json');//设置返回header头为json的类型	
		if($member =='' || $password==""){
			echo '{"msg":"用户名或密码不能为空!","result":"-1"}';
		}else{
			$result = member_login($member, md5($password . HASH_MD5));//取一条用户登陆的相关数据
			if($result==true){
				if($result['unlock'] == 0){//账户被锁定
					echo '{"msg":"Sorry,你的帐户被冻结!","result":"-2"}';
				}else{
					$this->set_member_session($member, $result['uid'], $member, time());//设置Session
					echo '{"msg":"登陆成功!","result":"0"}';	
				}
			}else{
				echo '{"msg":"用户名或密码错误!","result":"-3"}';
				session_destroy();
			}
		}
		die();
	}
	
	/**
	 * Ajax 获取当前登录会员信息
	*/
	public function ajax_get_member() {
		header('Content-Type:application/json');//设置返回header头为json的类型	
		$check_is_login = $this->check_member_login();//检查是否登录

		if($check_is_login == false){
			echo '{"state":"-1", "msg":"非法操作,请先登陆!", "result":""}';
		}else{
			$check_online = $this->check_member_ontime(); //检查会员登录是否超时
			if($check_online == true) {
				echo '{"state":"-2", "msg":"登录超时,请重新!", "result":""}';
			}else{
				$get_member_data = get_member_one($_SESSION['mid']);//根据ID取一条会员数据
				if(jz_is_array($get_member_data)){//返回是否数组
					$arr = array('user'=>$get_member_data['user'], 'reg_os'=>$get_member_data['reg_os'], 'reg_date'=>date('Y-m-d H:i:s' ,$get_member_data['reg_date']));
					$json_str = json_encode($arr);
					echo '{"state":0, "msg":"数据获取成功", "result":'.$json_str.'}';//返回JSON格式数据
				}else{
					echo '{"state":"-3!","msg":"数据获取失败", "result":""}';	
				}
			}
		}
		die();
	}	
	
	/**
	 * Ajax 获取站内消息
	*/
	public function ajax_get_messages() {
		header('Content-Type:application/json');//设置返回header头为json的类型	
		$check_is_login = $this->check_member_login();//检查是否登录

		if($check_is_login == false){
			echo '{"state":"-1", "msg":"非法操作,请先登陆!", "result":""}';
		}else{
			$check_online = $this->check_member_ontime(); //检查会员登录是否超时
			if($check_online == true) {
				echo '{"state":"-2", "msg":"登录超时,请重新!", "result":""}';
			}else{
				$totle = get_mes_nums();//消息数据总条数
				$data = get_mess_data();//取所有消息数据
				if(jz_is_array($data)){//返回是否数组
					foreach($data as $key=>$val) { 
						$arr[] = array('id'=>$val['id'],  'title'=>$val['title'], 'content'=>$val['content'], 'date'=>date('Y-m-d H:i:s' ,$val['add_time']));
					}
					$json_str = json_encode($arr);
					echo '{"state":0, "msg":"数据获取成功", "result":'.$json_str.'}';//返回JSON格式数据
				}else{
					echo '{"state":"-3!","msg":"数据获取失败", "result":""}';	
				}
			}
		}
		die();
	}

	/**
	 * Ajax 根据消息ID获取一条消息数据
	*/
	public function ajax_messages_detail($mess_id) {
		header('Content-Type:application/json');//设置返回header头为json的类型	
		$check_is_login = $this->check_member_login();//检查是否登录

		if($check_is_login == false){
			echo '{"state":"-1", "msg":"非法操作,请先登陆!", "result":""}';
		}else{
			$check_online = $this->check_member_ontime(); //检查会员登录是否超时
			if($check_online == true) {
				echo '{"state":"-2", "msg":"登录超时,请重新!", "result":""}';
			}else{
				$data = get_messages_one($mess_id);//根据ID取一条消息数据
				if($data){
					$arr = array('id'=>$data['id'], 'title'=>$data['title'], 'content'=>$data['content'], 'date'=>date('Y-m-d H:i:s' ,$data['add_time']));
					$json_str = json_encode($arr);
					echo '{"state":0, "msg":"数据获取成功", "result":'.$json_str.'}';//返回JSON格式数据
				}else{
					echo '{"state":"-3!","msg":"数据获取失败", "result":""}';	
				}
			}
		}
		die();			
	}
	
	/*
	 * @检查是SESSION或者COOKIE值是否存在
	*/
	public function check_member_login($type='session') {
		$method= !empty($type) ? 'session' : 'cookie';
		switch($method) {
			case 'session':
			if(!isset($_SESSION['seq']) && !isset($_SESSION['mid']) && !isset($_SESSION['member']) && !isset($_SESSION['time'])){
				return false;
			}else{
				return true;
			}
			break;
			case 'cookie':
			if(!isset($_COOKIE['seq']) && !isset($_COOKIE['mid']) && !isset($_COOKIE['member']) && !isset($_COOKIE['time'])){
				return false;
			}else{
				return true;
			}
			break;
		}
	}
	
	/**
	 * @用户登陆超时检查(秒) 默认30分钟有效期
	*/
	public function check_member_ontime($long = '1800') {
		$new_time = time();
		$onlinetime = $_SESSION['time'];
		if ($new_time - $onlinetime > $long) {		
			unset($_SESSION);                 
			session_destroy();
			return true;
		} else {
			$_SESSION['time'] = time();
			return false;
		}
	}

	/** 
	 * @注销登陆{销毁SESSION}
	*/
	public function member_logout() {
		unset($_SESSION);   //删除SESSION
		session_destroy();  //终结SESSION会话
		unset($_COOKIE);    //注销COOKIE
		header('Location:member_login.php');
	}
	
}