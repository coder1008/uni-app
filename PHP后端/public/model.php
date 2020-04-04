<?php
/**
 +----------------------------------------------------
 * DES：前台+后台数据处理
 * Date: 2020-03-11
 * Author:冰封
 +----------------------------------------------------
*/

//后台登录操作
function manager_login($user, $pass){
	global $db;
	$sql='SELECT `uid`,`user`,`pass` FROM `fz_manage_login` WHERE `user`="'.$user.'" AND `pass`="'.$pass.'"';
	$data = $db->get_one($sql);	
	return $data;
}

//写入登陆记录
function login_record($number, $uid, $user, $ip, $time){
	global $db;
	$sql='INSERT INTO `fz_login_record`(`seq_number`, `uid`, `user`, `ip`, `login_time`) VALUES("'.$number.'", "'.$uid.'", "'.$user.'", "'.$ip.'", "'.$time.'") ';
	$result=$db->query($sql);
	return $result;
}

//根据UID取一条后台登录用户数据
function get_one_data($uid){
	global $db;
	$sql='SELECT * FROM `fz_manage_login` WHERE `uid`="' . $uid . '"';
	$data=$db->get_one($sql);
	return $data;
}

//检查输入帐户是否已被使用	
function check_user($user){
	global $db;
	$data = $db->get_one('SELECT `user` FROM `fz_manage_login` WHERE `user`="'. $user .'"');
	if(jz_is_array($data)){
		return true;
	}else{
		return false;
	}
}

//添加后台登陆帐户
function create_admin_user($qid, $user, $pass, $add_time){
	global $db;
	$sql = 'INSERT INTO `fz_manage_login`(`qid`, `user`, `pass`, `create_time`) VALUES("'. $qid .'", "'. $user .'", "'. $pass .'", "'. $add_time .'")';
	$result = $db->query($sql);
	if($result == 1){
		return true;
	}else{
		return false;
	}	
}

//根据UID修改密码
function edit_pass($uid, $qid, $pass){
	global $db;
	$sql ='UPDATE `fz_manage_login` SET `qid`="'. $qid .'", `pass`="'. $pass .'" WHERE `uid`=' .$uid. '';
	$result = $db->query($sql);
	if($result == 1){
		return true;
	}else{
		return false;
	}
}

//统计后台帐户总数
function get_login_nums(){
	global $db;
	$sql = 'SELECT count(`uid`) as `login_nums` FROM `fz_manage_login`';
	$data = $db->get_all($sql);
	return $data[0]['login_nums'];
}

//获取系统所有用户并分页
function get_all_login($frist, $last){
	global $db;
	$sql = 'SELECT * FROM `fz_manage_login` ORDER BY `uid` DESC LIMIT '.$frist.', '.$last.'';
	$data = $db->get_all($sql);
	return $data;
} 

//根据UID取所有后台登录用户数据
function get_login_all($uid){
	global $db;
	$sql ='SELECT * FROM `fz_manage_login` WHERE `uid` in('.$uid.')';
	$data = $db->get_all($sql);
	return $data;	
}

//根据UID删除登录账户
function login_del($uid ){
	global $db;
	$result = $db->query('DELETE FROM `fz_manage_login` WHERE `uid` in('.$uid.')');
	if($result==1){
		return true;	
	}else{
		return false;
	}
}

//根据UID删除登录记录数据
function del_logins_record($uid){
	global $db;
	$sql = 'DELETE FROM `fz_login_record` WHERE `uid` in ('.$uid.')';
	$result = $db->query($sql);
	if($result==1){
		return true;	
	}else{
		return false;
	}	
}

//根据UID取最后一次登陆数据
function login_time_now($seq_num, $uid){ 
	global $db;
	$data = $db->get_one('SELECT * FROM `fz_login_record` WHERE `seq_number`="' . $seq_num .'" AND `uid`="' . $uid . '" ORDER BY `id` DESC ');
	if(jz_is_array($data)) {
		return $data;
	}
} 
	
//取上次登陆的一条数据
function pre_login_data($seq_num, $uid){ 
	global $db;
	$login_record_data = login_time_now($seq_num, $uid); //最后一次登陆记录
	$sql='SELECT * FROM `fz_login_record` WHERE `uid`="' . $uid . '"  AND `id`!="'.$login_record_data['id'] .'" ORDER BY `id` DESC';
	$pre_login_data=$db->get_one($sql);	
	if(jz_is_array($pre_login_data)) {
		return $pre_login_data;	
	}else{//没有上次登录记录则返回当前登录记录	
		return $login_record_data;
	}
}

//取所有用户登录记录数据总数
function count_record_alls($login_user){ 
	global $db;
	$login_rerord_user = isset($login_user) ? 'WHERE `user` LIKE "%'.$login_user.'%"'  : '';
	$sql = 'SELECT count(`id`) as `record_nums` FROM `fz_login_record` '.$login_rerord_user.'';
	$data = $db->get_all($sql);
	return $data[0]['record_nums'];
} 

//取所有用户登录记录数据并分页
function login_record_alls($login_user, $frist, $last){ 
	global $db;
	$login_rerord_user = isset($login_user) ? 'WHERE `user` LIKE "%'.$login_user.'%"'  : '';
	$sql ='SELECT * FROM `fz_login_record` '.$login_rerord_user.' ORDER BY `id` DESC LIMIT '.$frist.', '.$last.'';
	$data = $db->get_all($sql); 
	return $data;
} 

//根据UID取所有登录记录数据总数
function count_record($uid){ 
	global $db;
	$sql = 'SELECT count(`id`) as `record_nums` FROM `fz_login_record` WHERE `uid`='. $uid .'';
	$data = $db->get_all($sql);
	return $data[0]['record_nums'];
} 

//根据UID取所有登录记录数据并分页
function login_record_all($uid, $frist, $last){ 
	global $db;
	$sql ='SELECT * FROM `fz_login_record` WHERE `uid`="' . $uid . '" ORDER BY `id` DESC LIMIT '.$frist.', '.$last.'';
	$data = $db->get_all($sql); 
	return $data;
} 

//根据ID删除登录记录数据
function del_login_record($id){
	global $db;
	$sql = 'DELETE FROM `fz_login_record` WHERE `id` in('.$id.')';
	$result = $db->query($sql);
	if($result==1){
		return true;	
	}else{
		return false;
	}
}

//根据ID取一条站点配置数据
function get_web_config($id){
	global $db;
	$sql ='SELECT * FROM `fz_web_config` WHERE `id`='. $id .'';
	$data = $db->get_one($sql);
	return $data;		
}

//更新站点配置或公告
function update_web_config($content, $up_time, $id){
	global $db;
	$sql ="UPDATE `fz_web_config` SET `content`='". $content ."', `up_time`=$up_time WHERE `id`=$id";
	$result = $db->query($sql);
	if($result == 1){
		return true;	
	}else{
		return false;	
	}
}

//取会员数据总数
function get_member_count(){
	global $db;
	$sql = 'SELECT count(`uid`) as `nums` FROM `fz_member`';
	$data = $db->get_all($sql);
	return $data[0]['nums'];
}

//根据搜索条件取会员数据总数
function get_member_nums($start_time, $end_time, $os_lx, $user){
	global $db;
	$case_os = !empty($os_lx) ?  'AND `reg_os`="'.$os_lx.'"'  : '';
	$case_user = !empty($user) ?  'AND `user` LIKE "%'.$user.'%"'  : '';
	$sql = 'SELECT count(`uid`) as `member_nums` FROM `fz_member`
			WHERE `reg_date` BETWEEN "'.$start_time.'" AND "'.$end_time.'" 
			'.$case_os.' '.$case_user.' ORDER BY `uid` DESC';
	$data = $db->get_all($sql);
	return $data[0]['member_nums'];
}

//根据搜索条件取所有会员数据并分页显示
function get_all_member($start_time, $end_time, $os_lx, $user, $frist, $last){
	global $db;
	$case_os = !empty($os_lx) ?  'AND `reg_os`="'.$os_lx.'"'  : '';
	$case_user = !empty($user) ?  'AND `user` LIKE "%'.$user.'%"'  : '';
	$sql = 'SELECT * FROM `fz_member` 
			WHERE `reg_date` BETWEEN "'.$start_time.'" AND "'.$end_time.'" 
			'.$case_os.' '.$case_user.' 
			ORDER BY `uid` DESC LIMIT '.$frist.', '.$last.'';			
	$data = $db->get_all($sql);
	return $data;
} 

//根据ID删除所有会员数据
function del_member($uid){
	global $db;
	$sql = 'DELETE FROM `fz_member` WHERE `uid` in('.$uid.')';
	$result = $db->query($sql);
	if($result==1){
		return true;	
	}else{
		return false;
	}	
}

//根据ID更改会员账户状态
function update_state($uid, $state){
	global $db;
	$sql ='UPDATE `fz_member` SET `unlock`="'. $state .'" WHERE `uid`=' .$uid. '';
	$result = $db->query($sql);
	if($result == 1){
		return true;
	}else{
		return false;
	}
}

//添加站内消息
function create_mess_data($title, $nr, $time){
	global $db;
	$sql = 'INSERT INTO `fz_messages`(`title`, `content`, `add_time`) VALUES("'. $title .'", "'. $nr .'", "'. $time .'")';
	$result = $db->query($sql);
	if($result == 1){
		return true;
	}else{
		return false;
	}	
}

//取站内消息数据总数
function get_messages_count(){
	global $db;
	$sql = 'SELECT count(`id`) as `nums` FROM `fz_messages`';
	$data = $db->get_all($sql);
	return $data[0]['nums'];
}

//根据搜索条件取站内消息总数
function get_messages_nums($start_time, $end_time, $title){
	global $db;
	$case_title = !empty($title) ?  'AND `title` LIKE "%'.$title.'%"'  : '';
	$sql = 'SELECT count(`id`) as `mess_nums` FROM `fz_messages`
			WHERE `add_time` BETWEEN "'.$start_time.'" AND "'.$end_time.'" 
			'.$case_title.' ORDER BY `id` DESC';
	$data = $db->get_all($sql);
	return $data[0]['mess_nums'];
}

//根据搜索条件取所有站内消息数据并分页显示
function get_all_messages($start_time, $end_time, $title, $frist, $last){
	global $db;
	$case_title = !empty($title) ?  'AND `title` LIKE "%'.$title.'%"'  : '';
	$sql = 'SELECT * FROM `fz_messages` 
			WHERE `add_time` BETWEEN "'.$start_time.'" AND "'.$end_time.'" 
			'.$case_title.' ORDER BY `id` DESC LIMIT '.$frist.', '.$last.'';			
	$data = $db->get_all($sql);
	return $data;
} 

//根据ID删除站内消息数据
function del_messages($id){
	global $db;
	$sql = 'DELETE FROM `fz_messages` WHERE `id` in('.$id.')';
	$result = $db->query($sql);
	if($result==1){
		return true;	
	}else{
		return false;
	}	
}

//根据ID取一条站内消息数据
function get_one_mess($id){
	global $db;
	$sql='SELECT * FROM `fz_messages` WHERE `id`="' . $id . '"';
	$data=$db->get_one($sql);
	return $data;
}

//增加日志记录
function create_log_record($connten){
	global $db;
	$conntens=serialize($connten);
	$sql='INSERT INTO `fz_log_record`(`connten`, `c_time`) VALUES("'.$connten.'", "'.time().'") ';
	$result=$db->query($sql);
	return $result;
}

//统计日志记录总数
function log_record_nums(){
	global $db;
	$sql = 'SELECT count(`id`) as `log_record_nums` FROM `fz_log_record`';
	$data = $db->get_all($sql);
	return $data[0]['log_record_nums'];
}

//获取所有后台日志记录并分页显示
function log_record_all($frist, $last){
	global $db;
	$sql = 'SELECT * FROM `fz_log_record` ORDER BY `id` DESC LIMIT '.$frist.', '.$last.'';
	$data = $db->get_all($sql);
	return $data;
} 

//根据ID删除日志记录数据
function del_log_record($id){
	global $db;
	$sql = 'DELETE FROM `fz_log_record` WHERE `id` in('.$id.')';
	$result = $db->query($sql);
	if($result==1){
		return true;	
	}else{
		return false;
	}	
}


/* -------------------------------前台数据模型------------------------------- */

//检查会员账户帐户是否已被使用	
function check_member($member){
	global $db;
	$data = $db->get_one('SELECT `user` FROM `fz_member` WHERE `user`="'. $member .'"');
	if(jz_is_array($data)){
		return true;
	}else{
		return false;
	}
}

//注册会员数据
function create_member_data($member, $pass, $sex, $reg_os, $date){
	global $db;
	$sql='INSERT INTO `fz_member`(`user`, `pass`, `sex`, `reg_os`, `reg_date`) VALUES("'.$member.'", "'.$pass.'", "'.$sex.'", "'.$reg_os.'", "'.$date.'") ';
	$result=$db->query($sql);
	$insert_id = $db->insert_id();
	if($result == 1){
		return $insert_id;	
	}else{
		return $insert_id;	
	}
}

//会员登录操作
function member_login($user, $pass){
	global $db;
	$sql='SELECT `uid`,`user`,`pass`,`unlock` FROM `fz_member` WHERE `user`="'.$user.'" AND `pass`="'.$pass.'"';
	$data = $db->get_one($sql);	
	return $data;
}

//根据会员ID取一条会员数据
function get_member_one($uid){
	global $db;
	$sql='SELECT `uid`,`user`,`reg_os`, `reg_date` FROM `fz_member` WHERE `uid`="' . $uid . '"';
	$data=$db->get_one($sql);
	return $data;
}

//取站内信息总数
function get_mes_nums(){ 
	global $db;
	$sql = 'SELECT count(`id`) as `counts` FROM `fz_messages`';
	$data = $db->get_all($sql);
	return $data[0]['counts'];
} 

//取站内信息数据
function get_mess_data(){ 
	global $db;
	$sql = 'SELECT * FROM `fz_messages` ORDER BY `id` DESC';
	$data = $db->get_all($sql);
	return $data;	
} 

//根据ID取一条站内信息数据
function get_messages_one($id){
	global $db;
	$sql='SELECT * FROM `fz_messages` WHERE `id`="'.$id.'"';
	$data=$db->get_one($sql);
	return $data;
}

?>