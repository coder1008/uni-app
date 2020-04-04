<br />
<b>Warning</b>:  strftime(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in <b>F:\wwwroot\web\phpmyadmin\export.php</b> on line <b>254</b><br />
<br />
<b>Warning</b>:  strftime(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in <b>F:\wwwroot\web\phpmyadmin\libraries\common.lib.php</b> on line <b>1802</b><br />
<br />
<b>Warning</b>:  strftime(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in <b>F:\wwwroot\web\phpmyadmin\libraries\common.lib.php</b> on line <b>1803</b><br />
<br />
<b>Warning</b>:  strftime(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in <b>F:\wwwroot\web\phpmyadmin\libraries\common.lib.php</b> on line <b>1805</b><br />
-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 主机: 
-- 生成日期: 2020 �?03 �?12 �?13:16
-- 服务器版本: 5.6.47
-- PHP 版本: 5.6.20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `app_data`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_login_record`
-- 

CREATE TABLE `fz_login_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `seq_number` char(32) NOT NULL COMMENT '序列号',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `user` varchar(45) NOT NULL COMMENT '登录用户',
  `ip` varchar(20) NOT NULL COMMENT '登录IP',
  `login_time` int(11) NOT NULL COMMENT '登陆时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `fz_login_record`
-- 

INSERT INTO `fz_login_record` (`id`, `seq_number`, `uid`, `user`, `ip`, `login_time`) VALUES 
(1, 'd78f63d44553100f36dc69eef8870548', 1, 'admin', '192.168.83.1', 1584018629);

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_log_record`
-- 

CREATE TABLE `fz_log_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `connten` text NOT NULL COMMENT '内容',
  `c_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `fz_log_record`
-- 

INSERT INTO `fz_log_record` (`id`, `connten`, `c_time`) VALUES 
(1, 'admin用户于 2020-03-12 21:10:29 成功登入系统后台!', 1584018629);

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_manage_login`
-- 

CREATE TABLE `fz_manage_login` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `qid` int(11) NOT NULL COMMENT '权限ID{0=超级管理员 1=普通管理员}',
  `user` varchar(45) NOT NULL COMMENT '管理账号',
  `pass` char(32) NOT NULL COMMENT '管理密码',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `fz_manage_login`
-- 

INSERT INTO `fz_manage_login` (`uid`, `qid`, `user`, `pass`, `create_time`) VALUES 
(1, 0, 'admin', '8525eb68fc5250a8cd5726f4c64eb9fe', 1584017456);

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_member`
-- 

CREATE TABLE `fz_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `user` varchar(45) NOT NULL COMMENT '会员账号',
  `pass` char(32) NOT NULL COMMENT '登录密码',
  `unlock` int(11) NOT NULL DEFAULT '1' COMMENT '状态[0=锁定, 1=正常]',
  `sex` int(11) NOT NULL DEFAULT '1' COMMENT '性别',
  `reg_os` varchar(50) NOT NULL COMMENT '注册设备类型',
  `reg_date` int(11) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `fz_member`
-- 

INSERT INTO `fz_member` (`uid`, `user`, `pass`, `unlock`, `sex`, `reg_os`, `reg_date`) VALUES 
(1, 'test@qq.com', '0a996b09a5ab42535d92b5fc9ea09939', 1, 1, 'windwos', 1584017636);

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_messages`
-- 

CREATE TABLE `fz_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(150) NOT NULL COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息内容',
  `add_time` int(11) NOT NULL COMMENT '发布日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `fz_messages`
-- 

INSERT INTO `fz_messages` (`id`, `title`, `content`, `add_time`) VALUES 
(1, '哈利波特高清免费观看地址', '哈利波特高清免费观看地址http://baidu.com/v/hlbt.mp4', 1583946302),
(2, '星际探索高清免费观看地址', '星际探索高清免费观看地址', 1583946346);

-- --------------------------------------------------------

-- 
-- 表的结构 `fz_web_config`
-- 

CREATE TABLE `fz_web_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` text NOT NULL COMMENT '内容',
  `up_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `fz_web_config`
-- 

INSERT INTO `fz_web_config` (`id`, `content`, `up_time`) VALUES 
(1, 'a:7:{s:7:"web_url";s:16:"http://localhost";s:8:"web_name";s:31:"uni-app项目API开放和平台";s:7:"web_des";s:0:"";s:7:"web_key";s:0:"";s:8:"web_mail";s:12:"admin@qq.com";s:7:"web_icp";s:18:"京ICP备123456号";s:8:"web_code";s:0:"";}', 1583955913);
