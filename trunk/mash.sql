-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 10 月 28 日 08:53
-- 服务器版本: 5.0.90
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mashos`
--

-- --------------------------------------------------------

--
-- 表的结构 `f_attach`
--

CREATE TABLE IF NOT EXISTS `f_attach` (
  `aid` int(11) NOT NULL auto_increment,
  `number` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `ispub` tinyint(1) NOT NULL default '1',
  `check` tinyint(1) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  `type` varchar(32) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `number` (`number`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `f_attach`
--


-- --------------------------------------------------------

--
-- 表的结构 `f_config`
--

CREATE TABLE IF NOT EXISTS `f_config` (
  `cid` smallint(4) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `f_config`
--

INSERT INTO `f_config` (`cid`, `name`, `value`, `desc`) VALUES
(1, 'announce', '<span style="color:red">头部，后台可以替换</span>', '公告'),
(2, 'link', '脚部，后台可以替换', '友情链接');

-- --------------------------------------------------------

--
-- 表的结构 `f_event`
--

CREATE TABLE IF NOT EXISTS `f_event` (
  `eid` int(11) NOT NULL auto_increment,
  `ac` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `f_event`
--

INSERT INTO `f_event` (`eid`, `ac`, `score`, `desc`) VALUES
(1, 'Index_score', 1, '评分');

-- --------------------------------------------------------

--
-- 表的结构 `f_invite`
--

CREATE TABLE IF NOT EXISTS `f_invite` (
  `invite_id` int(11) NOT NULL auto_increment,
  `number` int(11) NOT NULL,
  `fnumber` int(11) NOT NULL,
  PRIMARY KEY  (`invite_id`),
  KEY `number` (`number`,`fnumber`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `f_invite`
--


-- --------------------------------------------------------

--
-- 表的结构 `f_out`
--

CREATE TABLE IF NOT EXISTS `f_out` (
  `oid` int(11) NOT NULL auto_increment,
  `number` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `score` double NOT NULL,
  PRIMARY KEY  (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `f_out`
--


-- --------------------------------------------------------

--
-- 表的结构 `f_rewrite`
--

CREATE TABLE IF NOT EXISTS `f_rewrite` (
  `rwid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`rwid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `f_rewrite`
--

INSERT INTO `f_rewrite` (`rwid`, `name`, `value`) VALUES
(2, '/grade/', '/Index/index/grade/'),
(3, '/rank/', '/Index/rank/type/'),
(4, '/pk/', '/Index/pk/type/'),
(6, '/avatar/', '/Index/getRankImg/number/'),
(7, '/index.php?m=Home&a=uavatar', '/Home/uavatar');

-- --------------------------------------------------------

--
-- 表的结构 `f_score`
--

CREATE TABLE IF NOT EXISTS `f_score` (
  `sid` int(11) NOT NULL auto_increment,
  `number` int(11) NOT NULL,
  `score` double NOT NULL,
  `type` varchar(32) NOT NULL,
  `grade` int(11) NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `number` (`number`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `f_score`
--

INSERT INTO `f_score` (`sid`, `number`, `score`, `type`, `grade`) VALUES
(14, 11111111, 0, 'beautiful', 2008),
(13, 111222211, 0, 'beautiful', 2008),
(12, 111222321, 0, 'beautiful', 2008),
(11, 11122221, 0, 'beautiful', 2008),
(10, 2008031059, 0, 'beautiful', 2008),
(9, 2008031049, 0, 'beautiful', 2008);

-- --------------------------------------------------------

--
-- 表的结构 `f_score_field`
--

CREATE TABLE IF NOT EXISTS `f_score_field` (
  `sfid` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `desc` varchar(32) NOT NULL,
  `score` int(11) NOT NULL,
  `sex` varchar(2) NOT NULL,
  PRIMARY KEY  (`sfid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `f_score_field`
--

INSERT INTO `f_score_field` (`sfid`, `name`, `desc`, `score`, `sex`) VALUES
(4, 'beautiful', '美女', 0, '女'),
(7, 'handsome', '帅哥', 0, '男');

-- --------------------------------------------------------

--
-- 表的结构 `f_score_log`
--

CREATE TABLE IF NOT EXISTS `f_score_log` (
  `slid` int(11) NOT NULL auto_increment,
  `number` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL default '',
  `score` double NOT NULL default '0',
  `fnumber` int(11) NOT NULL default '0',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY  (`slid`),
  KEY `number` (`number`,`fnumber`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `f_score_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `f_user`
--

CREATE TABLE IF NOT EXISTS `f_user` (
  `uid` int(10) NOT NULL auto_increment,
  `password` varchar(32) NOT NULL default '',
  `eara` varchar(6) default NULL,
  `department` varchar(18) default NULL,
  `grade` int(11) NOT NULL,
  `major` varchar(30) default NULL,
  `class` varchar(20) default NULL,
  `number` int(10) default NULL,
  `name` varchar(12) default NULL,
  `sex` varchar(3) default NULL,
  `avatar` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`uid`),
  KEY `grade` (`grade`),
  KEY `sex` (`sex`),
  KEY `number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `f_user`
--

INSERT INTO `f_user` (`uid`, `password`, `eara`, `department`, `grade`, `major`, `class`, `number`, `name`, `sex`, `avatar`) VALUES
(1, '', NULL, '计算机学院', 2008, '信息安全本科', '信安102', 2008031049, '张三', '女', 'Public/avatar/no.jpg'),
(2, '', NULL, '计算机学院', 2008, '信息安全本科', '信安103', 2008031059, '李三', '女', 'Public/avatar/no.jpg'),
(3, '', NULL, '网络工程学院', 2008, '计算机 ', '13', 11111111, '报纸', '女', 'Public/avatar/no.jpg'),
(4, '', NULL, '网络工程学院', 2008, '计算机 ', '13', 111222211, '报纸', '女', 'Public/avatar/no.jpg'),
(5, '', NULL, '网络工程学院', 2008, '计算机 ', '13', 11122221, '报纸', '女', 'Public/avatar/no.jpg'),
(6, '', NULL, '网络工程学院', 2008, '计算机 ', '13', 111222321, '报纸', '女', 'Public/avatar/no.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `f_user_count`
--

CREATE TABLE IF NOT EXISTS `f_user_count` (
  `number` bigint(20) NOT NULL,
  `type` varchar(32) NOT NULL,
  `sum` int(11) NOT NULL,
  KEY `number` (`number`,`type`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_user_count`
--

