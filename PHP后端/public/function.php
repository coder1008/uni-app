<?php
/** 
 +----------------------------------------------------
 * 功能描述:全局公共函数
 * date:2020-03-11
 * @Author:冰封
 +----------------------------------------------------
*/
 
/**
 * @反斜线替换
 */
function mystripslashes($var) {
	if($var){return is_array($var)?array_map('mystripslashes',$var):stripslashes($var);}
	return null;
}

/**
 * @转义 javascript iframe代码标记(防止XSS和框架挂马)
 */
function trim_script($str) {
	$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
	$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
	$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
	$str = preg_replace ( '/]]\>/si', ']] >', $str );
	return $str;
}	

/**
 * @是否数组 return bool;只适合用于一维数组...
 */
function jz_is_array($array) {
	if(is_array($array) && count($array) > 0){
	   return true;
	}else{
	   return false;
	}
}


/*
 * @递归创建目录
 */
function create_dir($dir){
	if(!is_dir($dir)){
		if(!is_dir(dirname($dir))){
			create_dir(dirname($dir));
			mkdir($dir,'0777');
		}else{
			mkdir($dir,'0777');
		}
	}
}


/**
 * @获取用户设备类型
 * @return string
 */
function get_client_os() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(strpos($agent, 'windows nt')) {
        $platform = 'windwos';
    }else if(strpos($agent, 'macintosh')) {
        $platform = 'mac os';
    }else if(strpos($agent, 'ipod')) {
        $platform = 'ipod';
    }else if(strpos($agent, 'ipad')) {
        $platform = 'ipad';
    }else if(strpos($agent, 'iphone')) {
        $platform = 'iphone';
    }else if (strpos($agent, 'android')) {
        $platform = 'android';
    }else if(strpos($agent, 'unix')) {
        $platform = 'unix';
    }else if(strpos($agent, 'linux')) {
        $platform = 'linux';
    }else {
        $platform = 'other';
    }
    return $platform;
}

/**
 * @PHP解决跨域
 */
function url_allow() {
	header('Access-Control-Allow-Origin: *');// 允许所有域名访问
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); //允许的请求类型
	header('Access-Control-Allow-Credentials: true'); //是否允许请求携带认证信息(cookies)
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); // 允许的请求头字段
}

/**
 * @创建CSV文件
 */
function create_csv($name, $title, $content) {
	header( "Cache-Control: public" );
	header( "Pragma: public" );
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=".$name . ".csv");
	header('Content-Type:APPLICATION/OCTET-STREAM');
	ob_start();
	$header_str =  iconv("utf-8",'gbk',"$title\n");
	$content_str = iconv("utf-8",'gbk',$content);
	ob_end_clean();
	echo $header_str;
	echo $content_str;
    exit;	
}		
?>