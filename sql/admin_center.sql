-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 06 月 15 日 14:12
-- 服务器版本: 5.0.77
-- PHP 版本: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `admin_center`
--

-- --------------------------------------------------------

--
-- 表的结构 `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `a_id` int(11) NOT NULL auto_increment COMMENT '自动ID',
  `cid` varchar(255) NOT NULL COMMENT '配置ID（可多个）',
  `lid` varchar(1024) NOT NULL COMMENT '类型ID(多个活动一起)',
  `gid` int(11) NOT NULL COMMENT '平台ID',
  `sid` text NOT NULL COMMENT '服ID（可多服）',
  `desc` varchar(255) NOT NULL COMMENT '备注',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`a_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动列表';

-- --------------------------------------------------------

--
-- 表的结构 `activity_config`
--

CREATE TABLE IF NOT EXISTS `activity_config` (
  `ac_id` int(11) NOT NULL auto_increment COMMENT '自动ID',
  `require_param` int(11) NOT NULL COMMENT '获得活动的触发条件值',
  `require_desc` varchar(255) NOT NULL COMMENT '参数描述',
  `role_exp` int(11) NOT NULL COMMENT '角色经验',
  `combat_exp` int(11) NOT NULL COMMENT '参战经验',
  `coin_bind` int(11) NOT NULL COMMENT '绑银',
  `gold_bind` int(11) NOT NULL COMMENT '礼金',
  `gold` int(11) NOT NULL COMMENT '元宝',
  `weiwang` int(11) NOT NULL COMMENT '威望',
  `xiuwei` int(11) NOT NULL COMMENT '修为',
  `xinghun` int(11) NOT NULL COMMENT '星魂',
  `guild_contri` int(11) NOT NULL COMMENT '宗族贡献',
  `energy` int(11) NOT NULL COMMENT '通神令',
  `blue_liehun` int(11) NOT NULL COMMENT '蓝色将魂',
  `purple_liehun` int(11) NOT NULL COMMENT '紫色将魂',
  `orange_liehun` int(11) NOT NULL COMMENT '橙色将魂',
  `item` text COMMENT '物品ID数量，序列化，最多16个',
  `description` varchar(1024) NOT NULL COMMENT '配置描述',
  `type` smallint(2) NOT NULL COMMENT '活动类型',
  `addtime` int(10) NOT NULL COMMENT '活动添加时间',
  `username` varchar(32) NOT NULL COMMENT '活动添加人',
  PRIMARY KEY  (`ac_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动配置表';

-- --------------------------------------------------------

--
-- 表的结构 `activity_list`
--

CREATE TABLE IF NOT EXISTS `activity_list` (
  `al_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `content` varchar(1024) NOT NULL COMMENT '活动内容',
  `desc` varchar(1024) NOT NULL COMMENT '活动描述',
  `schedule` varchar(255) NOT NULL COMMENT '当前进度,用$1代替数量或次数',
  `begin_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `type` smallint(2) NOT NULL COMMENT '活动类型',
  PRIMARY KEY  (`al_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动详细配置';

-- --------------------------------------------------------

--
-- 表的结构 `admingroup_sync`
--

CREATE TABLE IF NOT EXISTS `admingroup_sync` (
  `id` int(8) NOT NULL auto_increment,
  `gid` int(11) NOT NULL COMMENT '小组ID',
  `gname` varchar(20) NOT NULL COMMENT '小组名称',
  `groupbackground` varchar(30) default '0' COMMENT '用户组身份',
  `grights` text NOT NULL COMMENT '该小组的权限',
  `else` varchar(255) NOT NULL COMMENT '备注',
  `ctime` int(11) NOT NULL default '0' COMMENT '创建时间',
  `mtime` int(11) default NULL COMMENT '修改时间',
  `loc_group_id` smallint(3) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `loc_group_id` (`loc_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员分组表';

-- --------------------------------------------------------

--
-- 表的结构 `adminuser_sync`
--

CREATE TABLE IF NOT EXISTS `adminuser_sync` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `passport` varchar(100) NOT NULL COMMENT '登陆账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `gid` int(11) NOT NULL default '0' COMMENT '小组ID',
  `super` enum('YES','NO') NOT NULL default 'NO' COMMENT '超级管理员',
  `last_ip` varchar(255) NOT NULL COMMENT '上次登录IP',
  `ctime` int(11) NOT NULL default '0' COMMENT '创建时间',
  `mtime` int(11) default NULL COMMENT '修改时间',
  `else` varchar(255) NOT NULL COMMENT '备注',
  `loc_group_id` smallint(3) NOT NULL,
  `error_time` int(10) NOT NULL default '0' COMMENT '出错时间',
  `error_num` tinyint(1) NOT NULL default '0' COMMENT '出错次数',
  PRIMARY KEY  (`id`),
  KEY `loc_group_id` (`loc_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_log`
--

CREATE TABLE IF NOT EXISTS `admin_log` (
  `id` int(11) NOT NULL auto_increment,
  `admin_name` varchar(20) NOT NULL COMMENT '管理员名称',
  `event` tinyint(2) NOT NULL COMMENT '1登录',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `ip` varchar(15) NOT NULL COMMENT 'IP',
  `memo` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `admin_name` (`admin_name`,`ctime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台管理日志';

-- --------------------------------------------------------

--
-- 表的结构 `base_admin_user`
--

CREATE TABLE IF NOT EXISTS `base_admin_user` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL COMMENT '登录名',
  `status` tinyint(1) unsigned NOT NULL COMMENT '激活状态',
  `passwd` varchar(32) NOT NULL COMMENT '密码(md5)',
  `name` varchar(20) NOT NULL COMMENT '真实姓名',
  `description` text NOT NULL COMMENT '描述',
  `last_visit` int(10) unsigned NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '最后登录点IP',
  `last_addr` varchar(30) NOT NULL COMMENT '最后登录地点',
  `login_times` int(10) unsigned NOT NULL COMMENT '登录次数',
  `group_id` smallint(3) NOT NULL COMMENT '所属用户组ID',
  `srv_group_id` smallint(3) NOT NULL default '0' COMMENT '隶属平台',
  `error_ip` char(15) NOT NULL default '' COMMENT '出错的ip',
  `error_time` int(10) NOT NULL default '0' COMMENT '出错时间',
  `error_num` tinyint(2) NOT NULL default '0' COMMENT '出错次数',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台管理员帐号';

-- --------------------------------------------------------

--
-- 表的结构 `base_admin_user_group`
--

CREATE TABLE IF NOT EXISTS `base_admin_user_group` (
  `id` smallint(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL COMMENT '用户组名称',
  `menu` text NOT NULL COMMENT '菜单权限id,,',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台用户组';

-- --------------------------------------------------------

--
-- 表的结构 `base_srv_group`
--

CREATE TABLE IF NOT EXISTS `base_srv_group` (
  `id` smallint(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `ctype` tinyint(1) NOT NULL default '0' COMMENT '货币类型：0人民币，1港币，2台币',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务器分组';

-- --------------------------------------------------------

--
-- 表的结构 `base_srv_list`
--

CREATE TABLE IF NOT EXISTS `base_srv_list` (
  `id` mediumint(5) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '服务器名称',
  `group_id` smallint(3) NOT NULL COMMENT '分组ID',
  `url` varchar(60) NOT NULL COMMENT '游戏域名根目录如http://mhxx1.my4399.com/',
  `ctime` int(10) NOT NULL default '0' COMMENT '开服时间',
  `num` tinyint(2) NOT NULL default '1' COMMENT '服数，合服统计',
  `memo` varchar(150) NOT NULL COMMENT '描述',
  `is_beta` tinyint(1) NOT NULL default '0' COMMENT '是否为测试服',
  `drop_ratio` varchar(4) NOT NULL default '1.0' COMMENT '该服的掉落比率',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`,`group_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务器列表';

-- --------------------------------------------------------

--
-- 表的结构 `base_srv_list_new`
--

CREATE TABLE IF NOT EXISTS `base_srv_list_new` (
  `id` mediumint(5) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '服务器名称',
  `group_id` smallint(3) NOT NULL COMMENT '分组ID',
  `url` varchar(60) NOT NULL COMMENT '游戏域名根目录如http://mhxx1.my4399.com/',
  `ctime` int(10) NOT NULL default '0' COMMENT '开服时间',
  `memo` varchar(150) NOT NULL COMMENT '描述',
  `status` tinyint(1) NOT NULL default '0' COMMENT '服务器配置情况',
  `server_provider` tinyint(1) NOT NULL default '0' COMMENT '0我方，1对方',
  `is_live` tinyint(2) NOT NULL default '0' COMMENT '是否正式服',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`,`group_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='新开服务器列表';

-- --------------------------------------------------------

--
-- 表的结构 `charge`
--

CREATE TABLE IF NOT EXISTS `charge` (
  `id` int(11) NOT NULL auto_increment,
  `role_id` int(10) unsigned default NULL COMMENT '角色id',
  `acc_id` int(10) unsigned default NULL,
  `pay_no` varchar(100) default NULL COMMENT '订单号',
  `acc_name` varchar(100) default NULL COMMENT '平台名',
  `role_name` varchar(100) default NULL COMMENT '角色名',
  `gold` int(10) unsigned NOT NULL default '0' COMMENT '充值的元宝数',
  `cny` decimal(10,2) NOT NULL default '0.00' COMMENT '人民币',
  `hkd` decimal(10,2) NOT NULL default '0.00' COMMENT '港币',
  `twd` decimal(10,2) NOT NULL default '0.00' COMMENT '台币',
  `vnd` decimal(10,2) NOT NULL default '0.00',
  `myr` decimal(10,2) NOT NULL default '0.00' COMMENT '马来西亚币',
  `money` decimal(10,2) NOT NULL default '0.00' COMMENT '根据汇率转换后的人民币',
  `ctime` int(10) unsigned NOT NULL default '0' COMMENT '充值时间',
  `lv` smallint(3) NOT NULL COMMENT '人物等级',
  `cdate` char(10) NOT NULL COMMENT '充值日期，方便搜索，格式2010-08-08',
  `type` smallint(6) NOT NULL default '0' COMMENT '充值类型，1=普通充值，2=手机充值',
  `is_vip` tinyint(1) NOT NULL default '0' COMMENT '是否是VIP玩家',
  `group_id` smallint(4) NOT NULL COMMENT '平台id',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器id',
  `src_id` int(11) NOT NULL COMMENT '游戏后台数据原id',
  `realm` tinyint(1) NOT NULL default '9' COMMENT '所在阵营，默认为9，因为新手村为0',
  `career` tinyint(1) NOT NULL default '0' COMMENT '职业',
  `srv_role_id` char(15) NOT NULL default '0' COMMENT '处理全服统计人数时，不同服role_id相同的情况',
  PRIMARY KEY  (`id`),
  KEY `cdate` (`cdate`),
  KEY `role_id` (`role_id`),
  KEY `ctime` (`ctime`),
  KEY `srv_id` (`srv_id`),
  KEY `group_id` (`group_id`),
  KEY `realm` (`realm`,`career`),
  KEY `pay_no` (`pay_no`),
  KEY `srv_role_id` (`srv_role_id`),
  KEY `src_id` (`src_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='全服充值';

-- --------------------------------------------------------

--
-- 表的结构 `chat_autoban`
--

CREATE TABLE IF NOT EXISTS `chat_autoban` (
  `id` smallint(5) NOT NULL auto_increment,
  `has_math` tinyint(1) NOT NULL default '0' COMMENT '是否先判断有数字',
  `keyword` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='自动禁言关键字';

-- --------------------------------------------------------

--
-- 表的结构 `first_day30`
--

CREATE TABLE IF NOT EXISTS `first_day30` (
  `id` int(10) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `old_roles` smallint(4) NOT NULL COMMENT '老玩家',
  `remain_gold` mediumint(7) NOT NULL COMMENT '剩余元宝',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `srv_id` smallint(4) NOT NULL COMMENT '服务器ID',
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`),
  KEY `group_id` (`group_id`,`srv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='开服30天效果';

-- --------------------------------------------------------

--
-- 表的结构 `first_day_data`
--

CREATE TABLE IF NOT EXISTS `first_day_data` (
  `srv_id` smallint(4) NOT NULL COMMENT '服务器id',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `json` text NOT NULL,
  PRIMARY KEY  (`srv_id`),
  KEY `group_id` (`group_id`,`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新服数据监控分析';

-- --------------------------------------------------------

--
-- 表的结构 `item_detail`
--

CREATE TABLE IF NOT EXISTS `item_detail` (
  `id` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL COMMENT '物品ID',
  `item_name` varchar(124) NOT NULL COMMENT '物品名称',
  `is_bind` int(11) NOT NULL COMMENT '是否绑定',
  `color` int(11) NOT NULL COMMENT '颜色',
  `search_type` int(11) NOT NULL COMMENT '类型',
  `item_type` smallint(2) NOT NULL COMMENT '物品类型，用来区分是哪类型的物品',
  PRIMARY KEY  (`id`),
  KEY `item_id` (`item_id`),
  KEY `search_type` (`search_type`),
  KEY `is_bind` (`is_bind`),
  KEY `item_type` (`item_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='物品属性';

-- --------------------------------------------------------

--
-- 表的结构 `log_auction_detail`
--

CREATE TABLE IF NOT EXISTS `log_auction_detail` (
  `aid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `seller_role_id` int(11) NOT NULL COMMENT '出售人id',
  `seller_role_name` varchar(20) NOT NULL COMMENT '出售人名称',
  `seller_role_lev` smallint(3) NOT NULL COMMENT '出售人等级',
  `buyer_role_id` int(11) NOT NULL COMMENT '购买人id',
  `buyer_role_name` varchar(20) NOT NULL COMMENT '购买人名称',
  `buyer_role_lev` smallint(3) NOT NULL COMMENT '购买人等级',
  `sell_coin` int(11) NOT NULL default '0' COMMENT '拍卖铜钱',
  `sell_gold` int(11) NOT NULL default '0' COMMENT '拍卖元宝',
  `sell_base_id` int(11) NOT NULL default '0' COMMENT '拍卖物品id',
  `sell_item_name` varchar(20) NOT NULL default '' COMMENT '拍卖物品名称',
  `sell_item_quantity` smallint(3) NOT NULL default '1' COMMENT '叠加数量',
  `sell_money_type` tinyint(1) NOT NULL COMMENT '出售价格类型，0铜钱，1元宝',
  `sell_money` int(11) NOT NULL COMMENT '拍卖价格',
  `sell_unit_price` decimal(11,2) NOT NULL COMMENT '出售单价',
  `sell_time` smallint(3) NOT NULL COMMENT '拍卖时长',
  `sell_fee` int(11) NOT NULL COMMENT '拍卖手续费',
  `ctime` int(10) NOT NULL COMMENT '发生时间',
  `type` tinyint(1) NOT NULL COMMENT '类型，具体看auction.hrl',
  `group_id` smallint(5) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  `quality` tinyint(1) NOT NULL COMMENT '物品品质',
  `srv_seller_role_id` varchar(15) NOT NULL default '0' COMMENT '处理全服统计人数时，不同服role_id相同的情况',
  `srv_buyer_role_id` varchar(15) NOT NULL default '0' COMMENT '处理全服统计人数时，不同服role_id相同的情况',
  PRIMARY KEY  (`aid`),
  KEY `seller_role_id` (`seller_role_id`,`seller_role_name`,`buyer_role_id`,`buyer_role_name`),
  KEY `sell_base_id` (`sell_base_id`),
  KEY `ctime` (`ctime`),
  KEY `id` (`id`),
  KEY `group_id` (`group_id`,`srv_id`),
  KEY `type` (`type`),
  KEY `srv_seller_role_id` (`srv_seller_role_id`,`srv_buyer_role_id`),
  KEY `sell_money_type` (`sell_money_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖行详细日志表';

-- --------------------------------------------------------

--
-- 表的结构 `log_auction_stat`
--

CREATE TABLE IF NOT EXISTS `log_auction_stat` (
  `aid` int(11) NOT NULL auto_increment,
  `base_id` int(11) NOT NULL,
  `item_name` varchar(20) NOT NULL,
  `item_quality` tinyint(1) NOT NULL,
  `role_num` int(11) NOT NULL,
  `pnum` int(11) NOT NULL,
  `item_num` int(11) NOT NULL,
  `avg_money` decimal(11,2) NOT NULL,
  `min_money` decimal(11,2) NOT NULL,
  `max_money` decimal(11,2) NOT NULL,
  `buy_role_num` int(11) NOT NULL,
  `buy_pnum` int(11) NOT NULL,
  `buy_item_num` int(11) NOT NULL,
  `buy_avg_money` decimal(11,2) NOT NULL,
  `buy_min_money` decimal(11,2) NOT NULL,
  `buy_max_money` decimal(11,2) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  `group_id` smallint(3) NOT NULL,
  `ctime` int(10) NOT NULL,
  `lv_info` text NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `base_id` (`base_id`,`srv_id`),
  KEY `group_id` (`group_id`,`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖行消耗统计表';

-- --------------------------------------------------------

--
-- 表的结构 `log_charge_error`
--

CREATE TABLE IF NOT EXISTS `log_charge_error` (
  `id` int(11) NOT NULL auto_increment,
  `srv_name` varchar(20) NOT NULL,
  `srv_id` smallint(4) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `num` int(11) NOT NULL,
  `ctime` int(10) NOT NULL,
  `flag` tinyint(1) NOT NULL default '0' COMMENT '0还没有处理，1已经处理',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值数据检验结果';

-- --------------------------------------------------------

--
-- 表的结构 `log_comeback`
--

CREATE TABLE IF NOT EXISTS `log_comeback` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `role_num` int(10) NOT NULL COMMENT '回流人数',
  `lv` text NOT NULL,
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  PRIMARY KEY  (`id`),
  KEY `srv_id` (`srv_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='超过28天没有登录统计表';

-- --------------------------------------------------------

--
-- 表的结构 `log_error`
--

CREATE TABLE IF NOT EXISTS `log_error` (
  `id` int(11) NOT NULL,
  `src_id` int(11) NOT NULL,
  `ctime` int(10) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  `group_id` smallint(3) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`),
  KEY `srv_id` (`srv_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='各个服务器错误日志';

-- --------------------------------------------------------

--
-- 表的结构 `log_everyday_register`
--

CREATE TABLE IF NOT EXISTS `log_everyday_register` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL,
  `num` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `srv_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `srv_id` (`srv_id`),
  KEY `group_id` (`group_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='每天注册数';

-- --------------------------------------------------------

--
-- 表的结构 `log_feature_liveness`
--

CREATE TABLE IF NOT EXISTS `log_feature_liveness` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `join_lv` varchar(25) NOT NULL COMMENT '参与等级',
  `lv_num` mediumint(7) NOT NULL COMMENT '等级登录人数',
  `lname` varchar(25) NOT NULL COMMENT '功能名称',
  `role_num` mediumint(7) NOT NULL COMMENT '参与人数',
  `c` mediumint(7) NOT NULL COMMENT '参与次数',
  `accept_role_num` mediumint(7) NOT NULL COMMENT '接受人数',
  `accept_c` mediumint(7) NOT NULL COMMENT '接受次数',
  `finish_role_num` mediumint(7) NOT NULL COMMENT '完成人数',
  `finish_c` mediumint(7) NOT NULL COMMENT '完成次数',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`,`group_id`,`srv_id`),
  KEY `lname` (`lname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='功能活跃度';

-- --------------------------------------------------------

--
-- 表的结构 `log_gold`
--

CREATE TABLE IF NOT EXISTS `log_gold` (
  `id` int(11) NOT NULL auto_increment,
  `event` varchar(30) NOT NULL COMMENT '类型，具体看gold.cfg.php',
  `gold` int(11) NOT NULL COMMENT '涉及元宝总和',
  `ctime` int(11) NOT NULL COMMENT '日期',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `event` (`event`,`ctime`,`group_id`),
  KEY `srv_id` (`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宝消耗统计';

-- --------------------------------------------------------

--
-- 表的结构 `log_gold_stock`
--

CREATE TABLE IF NOT EXISTS `log_gold_stock` (
  `id` int(11) NOT NULL auto_increment,
  `all_gold` int(11) NOT NULL COMMENT '当天总充值元宝',
  `used_gold` int(11) NOT NULL COMMENT '花费掉元宝',
  `remain_gold` int(11) NOT NULL COMMENT '剩余元宝',
  `ctime` int(10) NOT NULL COMMENT '统计时间',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`),
  KEY `group_id` (`group_id`,`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宝库存统计';

-- --------------------------------------------------------

--
-- 表的结构 `log_item_consume`
--

CREATE TABLE IF NOT EXISTS `log_item_consume` (
  `aid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '物品名称',
  `base_id` int(11) NOT NULL COMMENT '物品ID',
  `num` int(11) NOT NULL COMMENT '当天消耗数量',
  `ctime` int(10) NOT NULL COMMENT '当天0时间戵',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `name` (`id`,`name`,`base_id`,`num`,`ctime`),
  KEY `group_id` (`group_id`,`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品消耗统计';

-- --------------------------------------------------------

--
-- 表的结构 `log_liveness`
--

CREATE TABLE IF NOT EXISTS `log_liveness` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间，不能重复',
  `lv10` int(10) NOT NULL COMMENT '等级>10级人数',
  `day2` int(10) NOT NULL COMMENT '二天连续登录人数',
  `realm` varchar(50) NOT NULL COMMENT '二天连续登录人数中各个阵营人数',
  `career` varchar(50) NOT NULL COMMENT '职业分布',
  `reg_num` int(7) NOT NULL COMMENT '当天注册人数',
  `login_times` mediumint(8) NOT NULL COMMENT '当天登录人数',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `srv_id` smallint(4) NOT NULL COMMENT '服务器ID',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`,`srv_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活跃度快照';

-- --------------------------------------------------------

--
-- 表的结构 `log_login_times`
--

CREATE TABLE IF NOT EXISTS `log_login_times` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `total_num` mediumint(8) NOT NULL default '0' COMMENT '总登录人数',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `src_id` int(11) NOT NULL COMMENT '原数据ID',
  `json` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `srv_id` (`srv_id`,`group_id`),
  KEY `src_id` (`src_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='每天在线人数统计表';

-- --------------------------------------------------------

--
-- 表的结构 `log_online`
--

CREATE TABLE IF NOT EXISTS `log_online` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `role_num` smallint(5) NOT NULL COMMENT '当前服务器总在线',
  `ctime` int(10) NOT NULL COMMENT '日志创建时间',
  `src_id` int(11) NOT NULL COMMENT '原数据ID',
  PRIMARY KEY  (`id`),
  KEY `src_id` (`src_id`),
  KEY `group_id` (`group_id`),
  KEY `srv_id` (`srv_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='各个时间段详细在线数据（每小时）';

-- --------------------------------------------------------

--
-- 表的结构 `log_online_time`
--

CREATE TABLE IF NOT EXISTS `log_online_time` (
  `id` int(11) NOT NULL auto_increment,
  `ol_time` int(10) NOT NULL COMMENT '当天在线时间累计',
  `ctime` int(10) NOT NULL COMMENT '本记录创建时间',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `group_id` smallint(4) NOT NULL COMMENT '平台ID',
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`),
  KEY `srv_id` (`srv_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_role_level`
--

CREATE TABLE IF NOT EXISTS `log_role_level` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL,
  `level_num` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `srv_id` int(11) NOT NULL,
  `src_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`),
  KEY `group_id` (`group_id`),
  KEY `srv_id` (`srv_id`),
  KEY `src_id` (`src_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='等级人数分布表';

-- --------------------------------------------------------

--
-- 表的结构 `log_seal`
--

CREATE TABLE IF NOT EXISTS `log_seal` (
  `id` int(11) NOT NULL auto_increment,
  `base_id` int(11) NOT NULL COMMENT '物品ID',
  `item_name` varchar(20) NOT NULL COMMENT '物品名称',
  `num` int(11) NOT NULL COMMENT '数量',
  `ctime` date NOT NULL COMMENT '创建日期',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `base_id` (`base_id`,`item_name`,`ctime`,`group_id`,`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='封印产出统计';

-- --------------------------------------------------------

--
-- 表的结构 `log_shop`
--

CREATE TABLE IF NOT EXISTS `log_shop` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `base_id` int(11) NOT NULL COMMENT '物品ID',
  `base_name` varchar(20) NOT NULL,
  `gold` int(10) NOT NULL COMMENT '销售元宝',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器Id',
  `group_id` smallint(3) NOT NULL COMMENT '平台id',
  PRIMARY KEY  (`id`),
  KEY `ctime` (`ctime`,`base_id`,`srv_id`,`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='物品销售日志';

-- --------------------------------------------------------

--
-- 表的结构 `log_silk`
--

CREATE TABLE IF NOT EXISTS `log_silk` (
  `id` int(11) NOT NULL auto_increment,
  `base_id` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `base_id` (`base_id`,`ctime`,`group_id`,`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='丝绸掉落日志表';

-- --------------------------------------------------------

--
-- 表的结构 `log_turnover`
--

CREATE TABLE IF NOT EXISTS `log_turnover` (
  `id` int(11) NOT NULL auto_increment,
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `json` text NOT NULL COMMENT '各个等级人数json',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  PRIMARY KEY  (`id`),
  KEY `srv_id` (`srv_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='超过28天没有登录统计表';

-- --------------------------------------------------------

--
-- 表的结构 `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `srv_id` smallint(5) NOT NULL COMMENT '服务器id',
  `group_id` tinyint(3) NOT NULL COMMENT '平台ID,,,',
  `online` smallint(5) NOT NULL COMMENT '在线人数',
  `nodes` tinyint(2) NOT NULL COMMENT '线路数量',
  `status` text NOT NULL COMMENT '状态',
  `yd_top_online` smallint(5) NOT NULL default '0' COMMENT '昨天最高在线',
  `ver` varchar(100) NOT NULL default '' COMMENT '版本号',
  PRIMARY KEY  (`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全服状态监控数据表';

-- --------------------------------------------------------

--
-- 表的结构 `role_ip_area`
--

CREATE TABLE IF NOT EXISTS `role_ip_area` (
  `aid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `area` varchar(10) NOT NULL COMMENT '市区',
  `login_num` int(10) NOT NULL COMMENT '人数',
  `charge_num` int(10) NOT NULL COMMENT '充值人数',
  `ctime` int(10) NOT NULL COMMENT '整点时间',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `login_num` (`login_num`,`ctime`),
  KEY `charge_num` (`charge_num`),
  KEY `group_id` (`group_id`,`srv_id`),
  KEY `area` (`area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='每天充值与登录人数地区分布';

-- --------------------------------------------------------

--
-- 表的结构 `role_ip_sp`
--

CREATE TABLE IF NOT EXISTS `role_ip_sp` (
  `aid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `sp` varchar(10) NOT NULL COMMENT '网络服务商名称',
  `login_num` int(10) NOT NULL COMMENT '人数',
  `charge_num` int(10) NOT NULL COMMENT '充值人数',
  `ctime` int(10) NOT NULL COMMENT '整点时间',
  `group_id` smallint(3) NOT NULL,
  `srv_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `login_num` (`login_num`,`ctime`),
  KEY `charge_num` (`charge_num`),
  KEY `group_id` (`group_id`,`srv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='每天充值与登录人数网络服务商分布';

-- --------------------------------------------------------

--
-- 表的结构 `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `id` int(10) NOT NULL auto_increment,
  `rid` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `bid` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rid` (`rid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `vip`
--

CREATE TABLE IF NOT EXISTS `vip` (
  `id` int(11) NOT NULL auto_increment,
  `role_num` mediumint(7) NOT NULL COMMENT 'VIP玩家数量',
  `group_id` smallint(3) NOT NULL COMMENT '平台ID',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器ID',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`,`srv_id`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='vip信息记录';

-- --------------------------------------------------------

--
-- 表的结构 `vip_rmb_role`
--

CREATE TABLE IF NOT EXISTS `vip_rmb_role` (
  `id` int(10) NOT NULL auto_increment,
  `group_id` smallint(4) NOT NULL COMMENT '平台id',
  `srv_id` smallint(5) NOT NULL COMMENT '服务器id',
  `role_id` int(11) NOT NULL default '0' COMMENT '角色id',
  `money` decimal(32,2) default NULL,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `last_chage` int(10) NOT NULL COMMENT '最后充值时间',
  `last_login` int(10) NOT NULL COMMENT '最后登录时间',
  `lv` smallint(3) NOT NULL default '0' COMMENT '人物等级',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`,`srv_id`,`role_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `base_admin_user` (`id`, `username`, `status`, `passwd`, `name`, `description`, `last_visit`, `last_ip`, `last_addr`, `login_times`, `group_id`, `srv_group_id`, `error_ip`, `error_time`, `error_num`) VALUES
(1, 'admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'ztadmin', '', 1337053609, '127.0.0.1', '本机地址  ', 195, 0, 0, '', 0, 0),
(2, 'faiy', 1, '609b10eb58fffd3362bd12c10dd03d45', 'faiy', 'faiy131411', 0, '', '', 0, 0, 1, '', 0, 0);