<?php
/*
 * 数据库备份+还原操作类库
  +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    冰封<QQ:574578944>
  +------------------------------------------------------------------------------
 */
class mysql_back{
	private $config;
	private $content;
	private $dbName = array();
	const DIR_SEP = DIRECTORY_SEPARATOR;//操作系统的目录分隔符

	public function __construct($config) {//初始化相关属性
		header ( "Content-type: text/html;charset=utf-8" );
		$this->config = $config;
		$this->connect();
	}
	
/**
 * 连接数据库
 *
 * @access private
 * @return void
*/
	private function connect() {
		if (mysql_connect($this->config['host']. ':' . $this->config['port'], $this->config['userName'], $this->config['userPassword'])) {
			mysql_query("SET NAMES '{$this->config['charset']}'");
			mysql_query("set interactive_timeout=24*3600");
		} else {
			$this->throwException('无法连接到数据库!');
		}
	}
	
/**
 * 设置欲备份的数据库
 *
 * @param string $dbName 数据库名(支持多个参数.默认为全部的数据库)
 * @access public
 * @return void
*/
	public function setDBName($dbName = '*') {
		if ($dbName == '*') {
			$rs = mysql_list_dbs();
			$rows = mysql_num_rows($rs);
			if($rows){
				for($i=0;$i<$rows;$i++){
					$dbName = mysql_tablename($rs,$i);
					$block = array('information_schema', 'mysql');
					if (!in_array($dbName, $block)) {
						$this->dbName[] = $dbName;
					}
				}
			} else {
				$this->throwException('没有任何数据库!');
			}
		} else {
			$this->dbName = func_get_args();
		}
	}
	
/**
 * 获取备份文件
 *
 * @param string $fileName 文件名
 * @access private
 * @return void
*/
	private function getFile($fileName) {
		$this->content = '';
		$fileName = $this->trimPath($this->config['path'] . self::DIR_SEP .$fileName);
		if (is_file($fileName)) {
			$ext = strrchr($fileName, '.');
			if ($ext == '.sql') {
				$this->content = file_get_contents($fileName);
			} elseif ($ext == '.gz') {
				$this->content = implode('', gzfile($fileName));
			} else {
				$this->throwException('无法识别的文件格式!');
			}
		} else {
			$this->throwException('文件不存在!');
		}
	}
	
/*
 * 备份文件
 * @access private
*/
	private function setFile() {
		$recognize = '';
		$recognize = implode('_', $this->dbName);
		$fileName = $this->trimPath($this->config['path'] . self::DIR_SEP . $recognize.'_'.date('YmdHis') . '_' . mt_rand(100000000,999999999) .'.sql');
		
		$path = $this->setPath($fileName);
		if ($path !== true) {
			$this->throwException("无法创建备份目录 '$path'");
		}
		if ($this->config['isCompress'] == 0) {
			if (!file_put_contents($fileName, $this->content, LOCK_EX)) {
				$this->throwException('写入文件失败,请检查磁盘空间或者权限!');
			}
		} else {
			if (function_exists('gzwrite')) {
				$fileName .= '.gz';
				if ($gz = gzopen($fileName, 'wb')) {
					gzwrite($gz, $this->content);
					gzclose($gz);
				} else {
					$this->throwException('写入文件失败,请检查磁盘空间或者权限!');
				}
			} else {
				$this->throwException('没有开启gzip扩展!');
			}
		}
		if ($this->config['isDownload']) {
			$this->downloadFile($fileName);
		}
	}
	
/**
* 将路径修正为适合操作系统的形式
*
* @param  string $path 路径名称
* @return string
*/
	private function trimPath($path) {
		return str_replace(array('/', '\\', '//', '\\\\'), self::DIR_SEP, $path);
	}

/**
 * 设置并创建目录
 *
 * @param $fileName 路径
 * @return mixed
 * @access private
*/
	private function setPath($fileName)	{
		$dirs = explode(self::DIR_SEP, dirname($fileName));
		$tmp = '';
		foreach ($dirs as $dir) {
			$tmp .= $dir . self::DIR_SEP;
			if (!file_exists($tmp) && !@mkdir($tmp, 0777))
			return $tmp;
		}
		return true;
	}
	
//备份文件下载	
	private function downloadFile($fileName) {
		ob_end_clean();
		header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($fileName));
		header('Content-Disposition: attachment; filename=' . basename($fileName));
		readfile($fileName);
	}
	
//给表名或者数据库名加上``
	private function backquote($str) {
		return "`{$str}`";
	}
	
//获取所有表	
	private function getTables($dbName){
		@$rs = mysql_list_tables($dbName);
		$rows = mysql_num_rows($rs);
		$dbprefix = $this->config['dbprefix'];
		for ($i=0; $i<$rows; $i++) {
			$tbName = mysql_tablename($rs, $i);
			if(substr($tbName,0,strlen($dbprefix)) == $dbprefix){ 
				$tables[] = $tbName;
			}
		}
		return $tables;
	}
	
 /*
 * 将数组按照字节数分割成小数组
 *
 * @param array $array  数组
 * @param int $byte     字节数
 * @return array
*/
	private function chunkArrayByByte($array, $byte = 5120) {
		$i=0;
		$sum=0;
		$return = array();
		foreach ($array as $v) {
			$sum += strlen($v);
			if ($sum < $byte) {
				$return[$i][] = $v;
			} elseif ($sum == $byte) {
				$return[++$i][] = $v;
				$sum = 0;
			} else {
				$return[++$i][] = $v;
				$i++;
				$sum = 0;
			}
		}
		return $return;
	}
	
//数据备份	
	public function backup() {
		$this->content = '/* This file is created by MySQLReback ' . date('Y-m-d H:i:s') . ' */';
		foreach ($this->dbName as $dbName) {
			$qDbName = $this->backquote($dbName);
			$rs = mysql_query("SHOW CREATE DATABASE {$qDbName}");
			if ($row = mysql_fetch_row($rs)) {
				mysql_select_db($dbName);
				$tables = $this->getTables($dbName);
				foreach ($tables as $table) {
					$table = $this->backquote($table);
					$tableRs = mysql_query("SHOW CREATE TABLE {$table}");
					if ($tableRow = mysql_fetch_row($tableRs)) {
						$this->content .= "\r\n /* 创建表结构 {$table}  */";
						$this->content .= "\r\n DROP TABLE IF EXISTS {$table};/* MySQLReback Separation */ {$tableRow[1]};/* MySQLReback Separation */";
						$tableDateRs = mysql_query("SELECT * FROM {$table}");
						$valuesArr = array();
						$values = '';
						while ($tableDateRow = mysql_fetch_row($tableDateRs)) {
							foreach ($tableDateRow as &$v) {
								$v = "'" . addslashes($v) . "'"; 
							}
							$valuesArr[] = '(' . implode(',', $tableDateRow) . ')';
						}
						$temp = $this->chunkArrayByByte($valuesArr);
						if (is_array($temp)) {
							foreach ($temp as $v) {
								$values = implode(',', $v) . ';/* MySQLReback Separation */';
								if ($values != ';/* MySQLReback Separation */') {
									$this->content .= "\r\n /* 插入数据 {$table} */";
									$this->content .= "\r\n INSERT INTO {$table} VALUES {$values}";
								}
							}
						}
					}
				}
			} else {
				$this->throwException('未能找到数据库!');
			}
		}
		if (!empty($this->content)) {//将数据和数据表结构写入SQL文件
			$this->setFile();
		}
		return true;
	}
	
//数据还原	
	public function recover($fileName) {
		$this->getFile($fileName);
		if (!empty($this->content)) {
			$content = explode(';/* MySQLReback Separation */', $this->content);
			foreach ($content as $i => $sql) {
				$sql = trim($sql);
				if (!empty($sql)) {
					$dbName = $this->dbName[0];
					if(!mysql_select_db($dbName)) $this->throwException('不存在的数据库!' . mysql_error());
					$rs = mysql_query($sql);
					if ($rs) {
						if (strstr($sql, 'CREATE DATABASE')) {
							$dbNameArr = sscanf($sql, 'CREATE DATABASE %s');
							$dbName = trim($dbNameArr[0], '`');
							mysql_select_db($dbName);
						}
					} else {
						$this->throwException('备份文件被损坏!' . mysql_error());
					}
				}
			}
		} else {
			$this->throwException('无法读取备份文件!');
		}
		return true;
	}
	
//抛出异常信息
	private function throwException($error) {
		throw new Exception($error);
	}
	
}
?>