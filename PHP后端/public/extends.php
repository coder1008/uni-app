<?php
/** 
 +----------------------------------------------------
 * @功能描述: 扩展函数 
 * @Date: 2020-03-11
 * @Author: 冰封
 +----------------------------------------------------
*/ 	
	
	//@取客户端IP地址
	function get_client_ip() {
	   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		   $ip = getenv("HTTP_CLIENT_IP");
	   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		   $ip = getenv("HTTP_X_FORWARDED_FOR");
	   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		   $ip = getenv("REMOTE_ADDR");
	   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		   $ip = $_SERVER['REMOTE_ADDR'];
	   else
		   $ip = "unknown";
	   return($ip);
	}

	/**
	 * 获取给定IP的物理地址
	 * @param string $ip
	 * @return string
	 */
	function convert_ip($ip) {
		$return = '';
		if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
			$iparray = explode('.', $ip);
			if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
				$return = '- LAN';
			} elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
				$return = '- Invalid IP Address';
			} else {
				$tinyipfile = dirname(__FILE__) . '/ipdate/tinyipdata.dat';
				$fullipfile = dirname(__FILE__) . '/ipdate/QQwry.dat';
				if(@file_exists($tinyipfile)) {
					$return = convert_ip_tiny($ip, $tinyipfile);
				} elseif(@file_exists($fullipfile)) {
					$return = convert_ip_full($ip, $fullipfile);
				}
			}
		}
		$return = iconv('GBK', 'UTF-8', $return);
		return $return;
	}

	/**
	 * @see convert_ip()
	 */
	function convert_ip_tiny($ip, $ipdatafile) {
			static $fp = NULL, $offset = array(), $index = NULL;
		
			$ipdot = explode('.', $ip);
			$ip    = pack('N', ip2long($ip));
		
			$ipdot[0] = (int)$ipdot[0];
			$ipdot[1] = (int)$ipdot[1];
		
			if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
				$offset = unpack('Nlen', fread($fp, 4));
				$index  = fread($fp, $offset['len'] - 4);
			} elseif($fp == FALSE) {
				return  '- Invalid IP data file';
			}
		
			$length = $offset['len'] - 1028;
			$start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);
		
			for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {
		
				if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
					$index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
					$index_length = unpack('Clen', $index{$start + 7});
					break;
				}
			}
		
			fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
			if($index_length['len']) {
				return '-'.fread($fp, $index_length['len']);
			} else {
				return '- Unknown';
			}
		
	}

	/**
	 * @see convert_ip()
	 */
	function convert_ip_full($ip, $ipdatafile) {
		if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
			return '- Invalid IP data file';
		}
		
		$ip = explode ( '.', $ip );
		$ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
		
		if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
			return;
		@$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
		if ($ipbegin < 0)
			$ipbegin += pow ( 2, 32 );
		@$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
		if ($ipend < 0)
			$ipend += pow ( 2, 32 );
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		
		$BeginNum = $ip2num = $ip1num = 0;
		$ipAddr1 = $ipAddr2 = '';
		$EndNum = $ipAllNum;
		
		while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
			$Middle = intval ( ($EndNum + $BeginNum) / 2 );
			
			fseek ( $fd, $ipbegin + 7 * $Middle );
			$ipData1 = fread ( $fd, 4 );
			if (strlen ( $ipData1 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
			if ($ip1num < 0)
				$ip1num += pow ( 2, 32 );
			
			if ($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
			
			$DataSeek = fread ( $fd, 3 );
			if (strlen ( $DataSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
			fseek ( $fd, $DataSeek );
			$ipData2 = fread ( $fd, 4 );
			if (strlen ( $ipData2 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
			if ($ip2num < 0)
				$ip2num += pow ( 2, 32 );
			
			if ($ip2num < $ipNum) {
				if ($Middle == $BeginNum) {
					fclose ( $fd );
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}
		
		$ipFlag = fread ( $fd, 1 );
		if ($ipFlag == chr ( 1 )) {
			$ipSeek = fread ( $fd, 3 );
			if (strlen ( $ipSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
			fseek ( $fd, $ipSeek );
			$ipFlag = fread ( $fd, 1 );
		}
		
		if ($ipFlag == chr ( 2 )) {
			$AddrSeek = fread ( $fd, 3 );
			if (strlen ( $AddrSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
			
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
			
			$AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
			fseek ( $fd, $AddrSeek );
			
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
		} else {
			fseek ( $fd, - 1, SEEK_CUR );
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
			
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
		}
		fclose ( $fd );
		
		if (preg_match ( '/http/i', $ipAddr2 )) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
		if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}
		
		return '- ' . $ipaddr;
	}
	
	/**
	* 字符串截取，支持中文和其他编码
	* @param string $str 需要转换的字符串
	* @param string $start 开始位置
	* @param string $length 截取长度
	* @param string $charset 编码格式
	* @param string $suffix 截断显示字符
	* @return string
	*/
	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr"))
			return mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			return iconv_substr($str,$start,$length,$charset);
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix) return $slice."…";
		return $slice;
	}
	
	/**
	+----------------------------------------------------------
	* 字符串截取，支持中文和其它编码
	* @param string $str 需要转换的字符串
	* @param string $length 截取长度
	* @param string $charset 编码格式
	* @param string $suffix 截断显示字符
	* @return string
	+----------------------------------------------------------
	*/
	function mstr($str, $length, $charset="utf-8", $suffix=true){
		return msubstr($str, 0, $length, $charset, $suffix);
	}
	
	/**
	 * 分页
	 * @$totle(总页)
	 * @$dosplay(每页显示条数,case=20) 
	 * @$url(默认的URL)
	*/
	function pageft($totle, $displaypg=20, $url='') {
		global $page,$firstcount,$pagenav,$_SERVER;
		$GLOBALS["displaypg"]=$displaypg;
		$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
		if(!$page) $page=1;
		if(!$url){ $url=$_SERVER["REQUEST_URI"];}
		//------------URL分析--------------
		$parse_url=parse_url($url);
		$url_query=isset($parse_url["query"])?$parse_url['query']:null; 
		if($url_query){
		$url_query=preg_replace("/[&]?page=$page/i","",$url_query);
		$url=str_replace($parse_url["query"],$url_query,$url);
		if($url_query) $url.="&page"; else $url.="page";
		}else {
		$url.="?page";
		}

		//页码计算：
		$lastpg=ceil($totle/$displaypg);
		$page=min($lastpg,$page);
		$prepg=$page-1; 
		$nextpg=($page==$lastpg ? 0 : $page+1); 
		$firstcount=($page-1)*$displaypg;

		//开始分页导航条代码：
		$pagenav="显示第 <B>".($totle?($firstcount+1):0)."</B>-<B>".min($firstcount+$displaypg,$totle)."</B> 条记录，共 $totle 条记录";

		//如果只有一页则跳出函数：
		if($lastpg<=1) return false;
		$pagenav.=" <a href='$url=1'>首页</a> ";
		if($prepg) $pagenav.=" <a href='$url=$prepg'>上一页</a> "; else $pagenav.=" 上一页 ";
		if($nextpg) $pagenav.=" <a href='$url=$nextpg'>下一页</a> "; else $pagenav.=" 下一页 ";
		$pagenav.=" <a href='$url=$lastpg'>尾页</a> ";

		//下拉跳转列表：
		$pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value'>\n";
		for($i=1;$i<=$lastpg;$i++){
		if($i==$page) $pagenav.="<option value='$i' selected>$i</option>\n";
		else $pagenav.="<option value='$i'>$i</option>\n";
		}
		  $pagenav.="</select> 页,共 $lastpg 页";
	}	
	
	/**
	 * @后台信息提示
	*/
	function show_msg($url, $show){
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta http-equiv="refresh" content="3; URL=' . $url . '" />
				<title>信息提示:</title>
				<style type="text/css">
				body,td,th{
					font-size: 12px;
				}
				body {
					margin-left: 0px;
					margin-top: 100px;
					margin-right: 0px;
					margin-bottom: 0px;
					line-height:200%;
					background-color:#EFEFEF;
				}
				a:link {font-size: 10pt;color: #000000;text-decoration: none;font-family: ""宋体"";}
				a:visited{font-size: 10pt;color: #000000;text-decoration: none;font-family: ""宋体"";}
				a:hover {color: red;font-family: ""宋体"";text-decoration: underline;}
				table{border:1px solid #222;background-color:#EEEEEE;}
				th{ background-color:#222;; font-size:14px;}
				td{padding:5px 10px 10px 10px;}
				.show{font-weight:bold;font-size:14px;}
				</style>
				</head>
				
				<body>
				<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
					<th height="34" style="color:#fff">提示信息</th>
				  </tr>
				  <tr align="center">
					<td height="141">
					<p class="show">'.$show.'<br /></p>
					<p>3秒后自动返回指定页面！<br /></p>
					<p>如果浏览器无法跳转，<a href="'.$url.'">请点击此处</a>。</p></td>
				  </tr>
				</table>
				</body>
				</html>
				';
		echo $msg;
		exit ();
	}//end msg	
?>