<?php
/*
  *DES:后台数据备份+还原等操作类
  *Author:冰封
  *date:2020-03-11
*/
//include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../public/backup.class.php';

class databackup{
	private $config;
	private $mr;

	 public function __construct($host, $port, $db_name, $user, $pass){//初始化相关属性       
		include_once 'mysql_back.class.php';	 
		$this->config = array(
			'host' => $host,
			'port' => $port,
			'dbname'=> $db_name,
			'userName' => $user,
			'userPassword' => $pass,
			'dbprefix' => '',
			'charset' => 'UTF8',
			'path' => '../uploads_data/backup_data/',//定义备份文件的路径
			'isCompress' => 1, //是否开启gzip压缩{0为不开启1为开启}
			'isDownload' => 0   //压缩完成后是否自动下载{0为不自动1为自动}
		);
		$this->mr = new mysql_back($this->config);
		
	}
	
//显示已备份的数据列表
	public function index(){		
		$path = $this->config['path'];	
		$fileArr = $this->MyScandir($path);	
		$list = array();	  
		foreach ($fileArr as $key => $value){	  
			if($key > 1){	 
				//获取文件创建时间        
				$fileTime = date('Y-m-d H:i',filemtime($path.$value));
				$fileSize = filesize($path.$value)/1024;
				//获取文件大小
				$fileSize = $fileSize < 1024 ? number_format($fileSize,2).' KB':
				number_format($fileSize/1024,2).' MB';
			//构建列表数组
				$list[]=array(
					'name' => $value,
					'time' => $fileTime,
					'size' => $fileSize
				);
			}
		}  
		return $list;	 
	}
         
	//备份数据库
	public function backup(){	  
	  $this->mr->setDBName($this->config['dbname']);
	  if($this->mr->backup()){	
		create_log_record($_SESSION['user'] . '用户点击了备份数据！');//写入操作日志
		show_msg('data_back.php','<font color=blue>备份成功！</font>');		
	 }else{	
	
		show_msg('data_back.php','<font color=red>备份失败！</font>');	
	 }	  
	}

	//删除数据备份
	public function deleteBak($filename){
		$files = explode(',', $filename);
		foreach($files as $file) {
			$un = @unlink($this->config['path'].$file);
		} 
		if($un){
			create_log_record($_SESSION['user'] . '用户删除了数据备份文件: '.$filename);//写入操作日志			
			show_msg('data_back.php','<font color=blue>数据备份文件删除成功！</font>');
		}else{			
			show_msg('data_back.php','<font color=red>数据备份文件删除失败！</font>');			
		}  		
	}

	//下载备份文件
	public function downloadBak($filename){	
		create_log_record($_SESSION['user'] . '用户点击了下载'.$filename.'数据备份！');//写入操作日志	
		$this->download($this->config['path'].$filename);  
	}

	//还原数据库
	public function recover($filename){
		$this->mr->setDBName($this->config['dbname']);
		if($this->mr->recover($filename)){ 
			create_log_record($_SESSION['user'] . '用户还原成功数据文件: '.$filename);//写入操作日志		
			show_msg('data_back.php','<font color=blue>数据还原成功！</font>');	
		}else{
			show_msg('data_back.php','<font color=red>数据还原失败！</font>');		
		}               	  
	}
	
	//获取目录下文件数组
	Public function MyScandir($FilePath='./',$Order=0){
		$FilePath = @opendir($FilePath);
		$fileArr  =array();
		 while($filename = @readdir($FilePath)) {
				$fileArr[] = $filename;
		 }
		$Order == 0 ? sort($fileArr) : rsort($fileArr);
		return $fileArr;
	}

	//公共下载方法
	Public function download($fileName){		  
	 ob_end_clean();
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-streamextension');
		header('Content-Length: '.filesize($fileName));
		header('Content-Disposition: attachment; filename='.basename($fileName));
		readfile($fileName);
	}
       
}