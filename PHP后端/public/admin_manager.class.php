<?php
/** 
 +----------------------------------------------------
 * @DES:后台操作
 * @Date:2020-03-11
 * @Author:冰封
 +----------------------------------------------------
*/

class admin_manager extends admin_login{
	
	/**
	 * @取当前登录用户权限ID
	 * mid (0=超级管理,1=普通管理)
	 * @超级管理=>全站最高权限,普通管理=>查看数据,修改自己密码
	*/
	public function check_power() {
		$login_data = get_one_data($_SESSION['uid']);//根据ID取一条后台登录用户数据
		return $login_data['qid'];//返回权限ID
	}
	
	/**
	 * @更新站点配置
	*/
	public function up_web_config() {
		$qid = $this->check_power();//检查权限
		$arr['web_url']  = htmlspecialchars($_POST['web_url']);
		$arr['web_name'] = htmlspecialchars($_POST['web_title']); 
		$arr['web_des']  = htmlspecialchars($_POST['web_meta']);
		$arr['web_key']  = htmlspecialchars($_POST['web_des']);
		$arr['web_mail'] = htmlspecialchars($_POST['web_admin']);
		$arr['web_icp']  = htmlspecialchars($_POST['web_icp']);
		$arr['web_code'] = htmlspecialchars($_POST['web_code']);		
		$data = serialize($arr);//序列化存储	
		if($qid == '0') {
			$up_res = update_web_config($data, time(), 1);//更新站点配置
			if($up_res) {
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 更新了全站配置信息');//写入操作日志
				show_msg('web_config.php', '<font color=blue>操作成功!</font>');
			}else{
				show_msg('web_config.php', '<font color=red>操作失败!</font>');
			}			
		}else{
			show_msg('web_config.php', '<font color="red">Soory,你无此操作权限!</font>');
		}	
	}	

	
	/**
	 * @ajax 更改会员账户状态
	*/
	public function up_member_state($id, $state){
		$qid = $this->check_power();
		if($qid == '0') {//超级管理员权限
			$result = update_state($id, $state);//根据UID更改账户状态
			if($result){
				//写入操作日志
				if($state == 1){
					create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 启用了ID为 '.$id.' 的会员数据');	
				}else{
					create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 停用了ID为 '.$id.' 的会员数据');//写入操作日志
				}
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}		
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		die();			
	}
	
	/**
	 * @ajax 删除会员数据
	*/
	public function delete_member_data($id){
		$qid = $this->check_power();
		if($qid == '0') {//超级管理员权限
			$result = del_member($id);//根据ID删除会员数据
			if($result){
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 删除了ID为 '.$id.' 的会员数据');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}		
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		die();			
	}
	
	/**
	 * ajax 提交站内消息操作
	*/
	public function create_messages($title, $conntent){
		$qid = $this->check_power();//检查当前管理账户权限
		if($qid == '0') {
			$create_mess = create_mess_data($title, $conntent, time());//创建消息
			if($create_mess) {
				create_log_record($_SESSION['user'] . ' 管理员发布了站内消息');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}						
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';	
		}
		die();
	}
	
	/**
	 * @ajax 删除站内消息数据
	*/
	public function del_mess_data($id){
		$qid = $this->check_power();
		if($qid == '0') {//超级管理员权限
			$result = del_messages($id);//根据ID删除站内消息数据
			if($result){
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 删除了ID为 '.$id.' 的站内消息数据');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}		
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		die();			
	}	
	
	/**
	 * ajax 创建系统账户操作
	*/
	public function create_login_data($q_id, $user, $pass){
		$qid = $this->check_power();//检查当前用户权限
		if($qid == '0') {
			$check_user = check_user($user);//检查输入账户是否被占用
			if($check_user){
				echo '{flag:false,msg:"此用户名已被占用,请更换其他帐户!"}';
			}else{
				$create_data = create_admin_user($q_id, $user, md5($pass . HASH_MD5), time());//创建系统账户
				if($create_data) {
					create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 创建了系统账户: '. $user);//写入操作日志
					echo '{flag:true,msg:"操作成功!"}';
				}else{
					echo '{flag:false,msg:"操作失败!"}';
				}					
			}
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';	
		}
		die();
	}
	
	/**
	 * 
	 * @ajax 根据ID修改管理密码
	 * 超级管理权限可修改所有用户数据, 普通管理只可修改自己的信息
	*/
	public function edit_login_pass($uid, $old_pass, $new_pass, $zt) {
		$qid = $this->check_power();
		$login_data_passwd = get_one_data($uid); 	//根据UID取一条数据
		$ps=md5($old_pass . HASH_MD5) == $login_data_passwd['pass'] ? TRUE  : FALSE ;//用户输入旧密码与数据库密码进行比对
		if($ps){
			if($qid == '0') {
				$up_pass = edit_pass($uid, $zt, md5($new_pass . HASH_MD5));	//修改密码
			}elseif($qid == '1' && $_SESSION['uid']==$uid){//只能修改自己的
				$up_pass = edit_pass($uid, $qid, md5($new_pass . HASH_MD5));
			}else{
				echo '{flag:false,msg:"Soory,你无此操作权限！"}';
				die();
			}
			if($up_pass) {
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 修改了系统账户:'.$login_data_passwd['user'].'的登录信息!');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}
		}else{
			echo '{flag:false,msg:"您输入原始密码错误;请重新输入!"}';
		}					
		die();
	}
	
	/**
	 * @ajax 根据ID删除系统账户
	*/
	public function del_login_data($uid){
		$qid = $this->check_power();
		if($qid == '0') {
			$login_data =get_login_all($uid);				//根据UID取所有登录账户数据
			$del_login = login_del($uid); 					//删除登录账户
			$del_record = del_logins_record($uid);			//根据UID删除一条登陆记录数据
			if($del_login && $del_record) {
				foreach($login_data as $val) {	
					create_log_record($_SESSION['user'] . '管理员于 '. date('Y-m-d H:i:s') .' 删除了系统账户:'.$val['user'].'以及他的登录记录数据！');//写入操作日志		
				}						
				if($_SESSION['uid']==$uid){		//如果删除的是自己则T线
					unset($_SESSION);   		//删除SESSION
					session_destroy();  		//终结SESSION会话
					unset($_COOKIE);   			//注销COOKIE
				}
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		die();			
	}	
	
	/** 
	 * @ajax删除管理登录记录数据
	*/
	public function ajax_del_login_record($id) {	
		$qid = $this->check_power();
		if($qid == '0') {//超级管理员权限
			$result = del_login_record($id);//根据ID删除所有登录记录数据
			if($result){
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 删除了ID为 '.$id.' 的登录记录数据');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}		
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		exit();	
	}
	
	/** 
	 * @ajax 删除日志数据
	*/
	public function ajax_del_log($id){
		$qid = $this->check_power();//检查权限
		if($qid == '0') {
			$result = del_log_record($id);//根据ID删除所有日志数据
			if($result){
				create_log_record($_SESSION['user'] . ' 管理员于 '. date('Y-m-d H:i:s') .' 删除了ID为 '.$id.' 的日志记录数据');//写入操作日志
				echo '{flag:true,msg:"操作成功!"}';
			}else{
				echo '{flag:false,msg:"操作失败!"}';
			}		
		}else{
			echo '{flag:false,msg:"Soory,你无此操作权限！"}';
		}
		die();		
	}

}