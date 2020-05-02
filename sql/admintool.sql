-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1:3306
-- 生成日期: 2013 年 06 月 29 日 13:44
-- 服务器版本: 5.5.19
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `admintool`
--

-- --------------------------------------------------------

--
-- 表的结构 `adminchange`
--

CREATE TABLE IF NOT EXISTS `adminchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `pid` varchar(20) NOT NULL DEFAULT '' COMMENT '标识ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跨服后台添加' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admingroup`
--

CREATE TABLE IF NOT EXISTS `admingroup` (
  `gid` int(11) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `gname` varchar(20) NOT NULL COMMENT '小组名称',
  `groupbackground` varchar(30) DEFAULT '0' COMMENT '用户组身份',
  `grights` text NOT NULL COMMENT '该小组的权限',
  `else` varchar(255) NOT NULL COMMENT '备注',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员分组表' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `adminlog`
--

CREATE TABLE IF NOT EXISTS `adminlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名称',
  `text` text COMMENT '主要动作',
  `ip` varchar(50) NOT NULL COMMENT '管理员IP',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `adminuser`
--

CREATE TABLE IF NOT EXISTS `adminuser` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `passport` varchar(100) NOT NULL COMMENT '登陆账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `super` enum('YES','NO') NOT NULL DEFAULT 'NO' COMMENT '超级管理员',
  `last_ip` varchar(255) NOT NULL COMMENT '上次登录IP',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `else` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `base_question`
--

CREATE TABLE IF NOT EXISTS `base_question` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '序号',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '类型',
  `quest` varchar(1000) NOT NULL DEFAULT '' COMMENT '问题',
  `opt1` varchar(250) DEFAULT '' COMMENT '选项1',
  `opt2` varchar(250) DEFAULT '' COMMENT '选项2',
  `opt3` varchar(250) DEFAULT '' COMMENT '选项3',
  `opt4` varchar(250) DEFAULT '' COMMENT '选项4',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='答题题库' AUTO_INCREMENT=1718 ;

-- --------------------------------------------------------

--
-- 表的结构 `charge`
--

CREATE TABLE IF NOT EXISTS `charge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '充值ID',
  `server_id` int(10) unsigned DEFAULT NULL COMMENT '服务器ID',
  `paynum` varchar(100) NOT NULL COMMENT '订单号',
  `mode` varchar(100) NOT NULL COMMENT '充值方式',
  `user` varchar(100) NOT NULL COMMENT '充值账号，即平台号',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `role_name` varchar(100) NOT NULL COMMENT '角色名称',
  `lv` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '��ɫ�ȼ�',
  `money` double(21,3) NOT NULL COMMENT '充值金额',
  `gold` int(10) unsigned NOT NULL COMMENT '充值元宝',
  `time` int(10) unsigned NOT NULL COMMENT '充值时间',
  `ticket` varchar(100) NOT NULL COMMENT '验证串',
  `ip` varchar(50) NOT NULL COMMENT '请求ip',
  `result` tinyint(4) NOT NULL COMMENT '充值结果',
  PRIMARY KEY (`id`),
  UNIQUE KEY `paynum` (`paynum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `chargepresent`
--

CREATE TABLE IF NOT EXISTS `chargepresent` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录id',
  `present_type` varchar(20) NOT NULL COMMENT '礼包类型',
  `plat_user_name` varbinary(64) NOT NULL COMMENT '平台账号名',
  `money` int(11) NOT NULL COMMENT '首充金额',
  `gold` int(11) NOT NULL COMMENT '充值元宝',
  `time` int(11) NOT NULL COMMENT '首充时间',
  `state` int(1) NOT NULL COMMENT '是否已经领取 1：已领取 0：未领取',
  `role_db_index` int(11) NOT NULL COMMENT '数据库标识',
  `role_id` int(11) NOT NULL COMMENT '领取角色ID',
  `role_name` varbinary(16) NOT NULL COMMENT '领取角色名',
  `get_time` int(11) NOT NULL COMMENT '领取时间',
  `present_id` int(11) NOT NULL COMMENT '领取到的物品ID',
  PRIMARY KEY (`id`),
  KEY `plat_user_name` (`plat_user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首充信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `count_login`
--

CREATE TABLE IF NOT EXISTS `count_login` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `roles` int(11) NOT NULL DEFAULT '0' COMMENT '登录角色数',
  `ips` int(11) NOT NULL DEFAULT '0' COMMENT '独立IP数',
  `pays` int(11) NOT NULL DEFAULT '0' COMMENT '充值角色数',
  `nopays` int(11) NOT NULL DEFAULT '0' COMMENT '无充值角色数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='登录人数统计' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `count_online_time`
--

CREATE TABLE IF NOT EXISTS `count_online_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `count_type` varchar(20) NOT NULL DEFAULT '' COMMENT '统计类型',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '统计时间戳',
  `total` int(10) NOT NULL DEFAULT '0' COMMENT '总数',
  `tenmin` int(10) NOT NULL DEFAULT '0' COMMENT '<10分钟',
  `thirtymin` int(10) NOT NULL DEFAULT '0' COMMENT '10-30分钟',
  `onehour` int(10) NOT NULL DEFAULT '0' COMMENT '30-1小时',
  `threehour` int(10) NOT NULL DEFAULT '0' COMMENT '1-3小时',
  `fivehour` int(10) NOT NULL DEFAULT '0' COMMENT '3-5小时',
  `sevenhour` int(10) NOT NULL DEFAULT '0' COMMENT '5-7小时',
  `moresevenhour` int(10) NOT NULL DEFAULT '0' COMMENT '7小时以上',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家在线时长统计' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `count_player_live`
--

CREATE TABLE IF NOT EXISTS `count_player_live` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '注册玩家数',
  `login2` int(11) NOT NULL DEFAULT '0' COMMENT '2日登录玩家数',
  `login3` int(11) NOT NULL DEFAULT '0' COMMENT '3日登录',
  `login4` int(11) NOT NULL DEFAULT '0' COMMENT '4日登录玩家数',
  `login5` int(11) NOT NULL DEFAULT '0' COMMENT '5日登录玩家数',
  `login6` int(11) NOT NULL DEFAULT '0' COMMENT '6日登录玩家数',
  `login7` int(11) NOT NULL DEFAULT '0' COMMENT '7日登录',
  `login8` int(11) NOT NULL DEFAULT '0' COMMENT '8日登录玩家数',
  `login15` int(11) NOT NULL DEFAULT '0' COMMENT '15日登录',
  `login30` int(11) NOT NULL DEFAULT '0' COMMENT '30日登录',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家留存率' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `count_return`
--

CREATE TABLE IF NOT EXISTS `count_return` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `plat_user_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'ƽ̨�˺�',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '������ȡʱ����',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='�ж�����������ȡ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `count_store`
--

CREATE TABLE IF NOT EXISTS `count_store` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `charge_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日充值元宝',
  `back_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日后台发放元宝',
  `consume_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日消耗元宝',
  `store_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '元宝库存总量（截止当天）',
  `store_gold_3` bigint(20) NOT NULL DEFAULT '0' COMMENT '元宝库存总量（3天登录）',
  `store_gold_7` bigint(20) NOT NULL DEFAULT '0' COMMENT '元宝库存总量（7天登录）',
  `add_goldbind` bigint(20) NOT NULL DEFAULT '0' COMMENT '绑定元宝产出',
  `back_goldbind` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日后台发放绑定元宝',
  `consume_goldbind` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日消耗绑定元宝',
  `store_goldbind` bigint(20) NOT NULL DEFAULT '0' COMMENT '绑定元宝库存总量（截止当天）',
  `store_goldbind_3` bigint(20) NOT NULL DEFAULT '0' COMMENT '绑定元宝库存总量（3天登录）',
  `store_goldbind_7` bigint(20) NOT NULL DEFAULT '0' COMMENT '绑定元宝库存总量（7天登录）',
  `add_coin` bigint(20) NOT NULL DEFAULT '0' COMMENT '铜钱产出',
  `consume_coin` bigint(20) NOT NULL DEFAULT '0' COMMENT '当日消耗铜钱',
  `store_coin` bigint(20) NOT NULL DEFAULT '0' COMMENT '铜钱库存总量',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '统计时间',
  `gm_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩充值',
  `player_chestshop_consume` bigint(20) NOT NULL DEFAULT '0' COMMENT '玩家祈福消耗',
  `gm_chestshop_consume` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩祈福消耗',
  `play_shop_consume` bigint(20) NOT NULL DEFAULT '0' COMMENT '玩家商城消耗',
  `gm_shop_consume` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩商城消耗',
  `gm_other_consume` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩其它消耗',
  `play_market` bigint(20) NOT NULL DEFAULT '0' COMMENT '玩家市场',
  `gm_market` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩市场',
  `gm_store_gold` bigint(20) NOT NULL DEFAULT '0' COMMENT '内玩库存',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宝/绑定元宝/铜钱库存统计表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `lantern_question`
--

CREATE TABLE IF NOT EXISTS `lantern_question` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `quest1` varchar(1000) NOT NULL DEFAULT '' COMMENT '题目1',
  `quest2` varchar(1000) NOT NULL DEFAULT '' COMMENT '题目2',
  `quest3` varchar(1000) NOT NULL DEFAULT '' COMMENT '题目3',
  `answer` varchar(250) DEFAULT '' COMMENT '选项1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宵灯谜题库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_battlefield`
--

CREATE TABLE IF NOT EXISTS `log_battlefield` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色名',
  `battle_index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '战区',
  `shengwang` int(10) NOT NULL DEFAULT '0' COMMENT '声望',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='战场日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_chestshop`
--

CREATE TABLE IF NOT EXISTS `log_chestshop` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `item_id` int(8) NOT NULL DEFAULT '0' COMMENT '物品类型ID',
  `num` int(5) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `is_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否绑定',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='祈福日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_comeback`
--

CREATE TABLE IF NOT EXISTS `log_comeback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `lv` smallint(3) NOT NULL COMMENT '角色当前等级',
  `ctime` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='回头率统计' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_count_online`
--

CREATE TABLE IF NOT EXISTS `log_count_online` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '查询时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '查询类型',
  `data` varbinary(64) NOT NULL COMMENT '在线人数',
  `total` varbinary(64) NOT NULL DEFAULT '0' COMMENT '登陆人数',
  `avg` varbinary(64) NOT NULL DEFAULT '0' COMMENT '平均在线',
  `high` varbinary(64) NOT NULL DEFAULT '0' COMMENT '最高在线',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家在线情况统计表（分类型）' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_disconnect`
--

CREATE TABLE IF NOT EXISTS `log_disconnect` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `server_name` varchar(30) NOT NULL DEFAULT '' COMMENT '服务器名',
  `reason` varchar(100) NOT NULL DEFAULT '' COMMENT '断线原因',
  `scene_id` int(10) NOT NULL DEFAULT '0' COMMENT '断线场景ID',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家断线日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_drop`
--

CREATE TABLE IF NOT EXISTS `log_drop` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL COMMENT '操作时间',
  `monster_id` int(10) NOT NULL COMMENT '怪物ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `role_name` varbinary(64) NOT NULL COMMENT '角色名',
  `item_id` int(11) NOT NULL COMMENT '物品ID',
  `is_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否绑定',
  `scene_id` int(10) NOT NULL DEFAULT '0' COMMENT '场景ID',
  `pos` varchar(32) NOT NULL DEFAULT '' COMMENT '坐标',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `monster_id` (`monster_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='BOSS掉落日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_equipment`
--

CREATE TABLE IF NOT EXISTS `log_equipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间戳',
  `type` varchar(50) NOT NULL COMMENT '操作类型',
  `status` varchar(50) NOT NULL COMMENT '操作结果',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `role_name` varchar(100) NOT NULL COMMENT '角色名称',
  `equip_id` varchar(100) NOT NULL COMMENT '装备ID',
  `equip_name` varchar(255) NOT NULL COMMENT '装备名称',
  `cost_stuff_id` int(10) NOT NULL COMMENT '消耗物品ID',
  `strengthen_level` varchar(32) DEFAULT NULL COMMENT '强化等级',
  `stuff_is_bind` tinyint(4) DEFAULT '0' COMMENT '消耗品是否绑定',
  `has_rune` tinyint(4) DEFAULT NULL COMMENT '是否使用保护符或者完美符',
  `rune_item_id` int(10) DEFAULT NULL COMMENT '符物品ID',
  `cost_coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消耗银两',
  `cost_gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消耗元宝',
  `flush_attr_type` varchar(255) NOT NULL DEFAULT '' COMMENT '属性类型',
  `flush_attr_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  `new_flush_attr_type` varchar(255) NOT NULL DEFAULT '' COMMENT '操作后属性类型',
  `new_flush_attr_value` varchar(255) NOT NULL DEFAULT '' COMMENT '操作后属性值',
  `hold_index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '第几个孔',
  `gemstone_name` varchar(100) NOT NULL DEFAULT '' COMMENT '宝石名称',
  `gemstone_id` int(10) NOT NULL DEFAULT '0' COMMENT '宝石ID',
  `gemstone_is_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '宝石是否绑定',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='武器装备操作记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_functionstats`
--

CREATE TABLE IF NOT EXISTS `log_functionstats` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '功能类型',
  `times` int(5) NOT NULL DEFAULT '0' COMMENT '参与次数',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='功能活跃度统计数据' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_gold_shop`
--

CREATE TABLE IF NOT EXISTS `log_gold_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `trade_timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '交易时间',
  `trade_type` varchar(32) NOT NULL DEFAULT '' COMMENT '交易类型',
  `trade_result` varchar(32) NOT NULL DEFAULT '' COMMENT '交易结果',
  `buyer_db_index` int(11) NOT NULL DEFAULT '0' COMMENT '买家数据库ID',
  `buyer_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `buyer_name` varchar(64) NOT NULL DEFAULT '' COMMENT '买家角色名',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '物品ID',
  `price_type` int(11) NOT NULL DEFAULT '0' COMMENT '购买类型。0是所有元宝,1是绑定元宝,2是非绑,3是银两',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `total_price` int(11) NOT NULL DEFAULT '0' COMMENT '交易总额',
  PRIMARY KEY (`id`),
  KEY `buyer_name` (`buyer_name`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宝商店交易记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_instant_online`
--

CREATE TABLE IF NOT EXISTS `log_instant_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) DEFAULT '0' COMMENT '时间',
  `thread_num` int(11) DEFAULT '0' COMMENT '在线人数',
  `role_num` int(11) DEFAULT '0' COMMENT '总人数',
  `thread_data` int(11) NOT NULL DEFAULT '0' COMMENT '产生数据的状态：0失败状态，1成功状态',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='历史在线' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_item`
--

CREATE TABLE IF NOT EXISTS `log_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL COMMENT '操作时间',
  `role_id` int(10) unsigned NOT NULL COMMENT '玩家ID',
  `role_name` varbinary(100) NOT NULL COMMENT '玩家名',
  `blueprint_id` int(10) unsigned DEFAULT NULL COMMENT '配方ID',
  `cost_item_name` varchar(100) DEFAULT NULL COMMENT '原物品名称',
  `cost_id` int(10) unsigned DEFAULT NULL COMMENT '原物品ID',
  `cost_bind_num` int(10) unsigned DEFAULT NULL COMMENT '原物品绑定数量',
  `cost_nobind_num` int(10) unsigned DEFAULT NULL COMMENT '原物品非绑数量',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '新物品绑定ID、或者宝石合成里的新宝石ID',
  `product_bind_num` int(10) unsigned DEFAULT NULL COMMENT '新物品绑定数量',
  `product_nobind_num` int(10) unsigned DEFAULT NULL COMMENT '新物品非绑ID',
  `product_item_name` varchar(100) DEFAULT NULL COMMENT '新物品名称',
  `cost_coin` int(10) unsigned DEFAULT NULL COMMENT '合成消耗银两',
  `end_level` int(10) unsigned DEFAULT NULL COMMENT '批量合成宝石等级',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品合成日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_jingjie`
--

CREATE TABLE IF NOT EXISTS `log_jingjie` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '玩家名字',
  `gengu_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '境界类型',
  `gengu_level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '境界等级',
  `gengu_max_level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '境界历史最高等级',
  `consume_gold` int(11) NOT NULL DEFAULT '0' COMMENT '消费元宝',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='境界提升日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_knapsack`
--

CREATE TABLE IF NOT EXISTS `log_knapsack` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `op_timestamp` int(10) unsigned NOT NULL COMMENT '操作时间',
  `op_type` varchar(32) NOT NULL COMMENT '操作类型：CertainPut为物品放置(获得),Use为使用、Discard为丢弃、ConsumeItemByIndex和ConsumeItem为消耗',
  `op_result` varchar(32) NOT NULL COMMENT '操作结果',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `role_name` varbinary(100) NOT NULL COMMENT '角色名',
  `item_name` varchar(100) DEFAULT NULL COMMENT '物品名称',
  `item_id` int(10) unsigned DEFAULT NULL COMMENT '物品的ID,当操作类型为ConsumeItem时,这里记录的是物品的非绑ID',
  `op_item_num` int(10) unsigned DEFAULT NULL COMMENT '操作物品的数量,当操作类型为ConsumeItem时,这里记录的是物品的非绑数量',
  `surplus_item_num` int(10) unsigned DEFAULT NULL COMMENT '剩余物品数量',
  `color` tinyint(4) NOT NULL COMMENT '装备颜色：2绿装、3蓝装、4紫装。属于物品放置[CertainPut]时专用字段',
  `is_equipment` tinyint(4) NOT NULL COMMENT '物品是否装备，1为是，0为否。属于物品放置[CertainPut]时专用字段',
  `reason` varchar(100) DEFAULT NULL COMMENT '失败原因、使用原因等',
  `drop_monster_id` int(10) NOT NULL DEFAULT '0' COMMENT '怪物ID',
  `item_param` varchar(255) DEFAULT NULL COMMENT '装备参数',
  PRIMARY KEY (`id`),
  KEY `op_timestamp` (`op_timestamp`,`op_item_num`,`color`,`is_equipment`,`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品放置、使用、丢弃、消耗记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_login`
--

CREATE TABLE IF NOT EXISTS `log_login` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型：登录或者下线',
  `status` varchar(10) NOT NULL DEFAULT '' COMMENT '状态：成功或者失败',
  `ip` int(10) NOT NULL DEFAULT '0' COMMENT 'ip地址',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家登录日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_mail`
--

CREATE TABLE IF NOT EXISTS `log_mail` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '操作类型',
  `status` varchar(100) NOT NULL DEFAULT '' COMMENT '状态',
  `rec_uid` int(10) NOT NULL DEFAULT '0' COMMENT '收件人ID',
  `send_uid` int(10) NOT NULL DEFAULT '0' COMMENT '发件人ID',
  `send_name` varchar(100) NOT NULL DEFAULT '' COMMENT '发件人名称',
  `subject` varchar(1000) NOT NULL DEFAULT '' COMMENT '邮件标题',
  `coin_bind` int(10) NOT NULL DEFAULT '0' COMMENT '绑定铜币',
  `gold` int(10) NOT NULL DEFAULT '0' COMMENT '元宝',
  `gold_bind` int(10) NOT NULL DEFAULT '0' COMMENT '绑定元宝',
  `item_id1` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID1',
  `item_num1` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量1',
  `item_param1` varchar(255) NOT NULL DEFAULT '' COMMENT '物品参数1',
  `item_id2` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID2',
  `item_num2` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量2',
  `item_param2` varchar(255) NOT NULL DEFAULT '' COMMENT '物品参数2',
  `item_id3` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID3',
  `item_num3` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量3',
  `item_param3` varchar(255) NOT NULL DEFAULT '' COMMENT '物品参数3',
  `virtual_items` varchar(255) NOT NULL DEFAULT '0' COMMENT '虚拟物品参数',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `rec_uid` (`rec_uid`),
  KEY `send_uid` (`send_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家邮件日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_marry`
--

CREATE TABLE IF NOT EXISTS `log_marry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `op_type` varchar(20) NOT NULL DEFAULT '' COMMENT '婚礼阶段',
  `marry_level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '婚礼类型',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '玩家AID',
  `user_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家A名字',
  `other_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '玩家BID',
  `other_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家B名字',
  PRIMARY KEY (`id`),
  KEY `op_timestamp` (`op_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='婚姻日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_money_coin`
--

CREATE TABLE IF NOT EXISTS `log_money_coin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `op_timestamp` int(10) unsigned NOT NULL COMMENT '操作时间',
  `op_type` varchar(100) NOT NULL COMMENT '操作类型',
  `op_result` varchar(100) NOT NULL COMMENT '操作结果',
  `role_db_index` tinyint(4) NOT NULL COMMENT '玩家数据库ID',
  `role_id` int(10) unsigned NOT NULL COMMENT '玩家ID',
  `role_name` varbinary(100) NOT NULL COMMENT '玩家名',
  `lv` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `use_for` varchar(100) NOT NULL COMMENT '用途',
  `use_coin` int(10) unsigned NOT NULL COMMENT '消耗银两',
  `use_coin_bind` int(10) unsigned NOT NULL COMMENT '消耗绑定银两',
  `add_coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加银两',
  `add_coin_bind` int(10) unsigned NOT NULL COMMENT '添加绑定银两',
  `remain_coin` int(10) unsigned NOT NULL COMMENT '剩余银两',
  `remain_coin_bind` int(10) unsigned NOT NULL COMMENT '剩余绑定银两',
  PRIMARY KEY (`id`),
  KEY `use_for` (`use_for`),
  KEY `add_coin` (`add_coin`),
  KEY `add_coin_bind` (`add_coin_bind`),
  KEY `use_coin` (`use_coin`),
  KEY `use_coin_bind` (`use_coin_bind`),
  KEY `op_timestamp` (`op_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='银两使用记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_money_gold`
--

CREATE TABLE IF NOT EXISTS `log_money_gold` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `op_timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `op_type` varchar(32) NOT NULL COMMENT '操作类型',
  `op_result` varchar(32) NOT NULL COMMENT '操作结果',
  `role_db_index` int(11) NOT NULL DEFAULT '0' COMMENT '角色数据库ID',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `role_name` varbinary(64) NOT NULL COMMENT '角色名',
  `lv` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `use_for` varchar(32) NOT NULL COMMENT '用途',
  `use_gold` int(11) NOT NULL DEFAULT '0' COMMENT '使用元宝',
  `use_gold_bind` int(11) NOT NULL DEFAULT '0' COMMENT '使用绑定元宝',
  `add_gold` int(11) NOT NULL DEFAULT '0' COMMENT '添加元宝',
  `add_gold_bind` int(11) NOT NULL DEFAULT '0' COMMENT '添加绑定元宝',
  `remain_gold` int(11) NOT NULL COMMENT '剩余元宝',
  `remain_gold_bind` int(11) NOT NULL COMMENT '剩余绑定元宝',
  PRIMARY KEY (`id`),
  KEY `role_name` (`role_name`),
  KEY `use_for` (`use_for`),
  KEY `op_timestamp` (`op_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元宝消使用录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_monitor`
--

CREATE TABLE IF NOT EXISTS `log_monitor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '玩家名字',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '等级',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '元宝',
  `bind_gold` int(11) NOT NULL DEFAULT '0' COMMENT '绑定元宝',
  `coin_bind` int(11) NOT NULL DEFAULT '0' COMMENT '绑定铜币',
  `gold_get` int(11) NOT NULL DEFAULT '0' COMMENT '获得元宝',
  `gold_consum` int(11) NOT NULL DEFAULT '0' COMMENT '消费元宝',
  `nobind_item_num` int(11) NOT NULL DEFAULT '0' COMMENT '非绑物品数量',
  `p2ptrade_num` int(11) NOT NULL DEFAULT '0' COMMENT '交易数量',
  `publicsale_num` int(11) NOT NULL DEFAULT '0' COMMENT '拍卖数量',
  `sendmail_num` int(11) NOT NULL DEFAULT '0' COMMENT '邮件发送次数',
  `fetch_attachment_num` int(11) NOT NULL DEFAULT '0' COMMENT '提取附件次数',
  `guild_store_oper_num` int(11) NOT NULL DEFAULT '0' COMMENT '军团仓库的操作次数',
  `shop_buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '商店购买次数',
  `chest_shop_buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '祈福次数',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家行为监控' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_mount`
--

CREATE TABLE IF NOT EXISTS `log_mount` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '操作类型',
  `status` varchar(10) NOT NULL DEFAULT '' COMMENT '状态：成功或者失败',
  `mount_name` varchar(100) NOT NULL DEFAULT '' COMMENT '坐骑名称',
  `mount_id` varchar(100) NOT NULL DEFAULT '' COMMENT '坐骑ID',
  `mount_level` varchar(100) NOT NULL DEFAULT '' COMMENT '坐骑等级',
  `mount_index` int(10) NOT NULL DEFAULT '0' COMMENT '坐骑索引',
  `feed_exp` varchar(100) NOT NULL DEFAULT '' COMMENT '培养经验',
  `feed_level` varchar(100) NOT NULL DEFAULT '' COMMENT '培养等级',
  `feed_times` int(4) NOT NULL DEFAULT '0' COMMENT '培养次数',
  `quality` varchar(100) NOT NULL DEFAULT '' COMMENT '品质',
  `flush_value_1` varchar(100) NOT NULL DEFAULT '' COMMENT '驯化属性1',
  `flush_value_2` varchar(100) NOT NULL DEFAULT '' COMMENT '驯化属性2',
  `flush_value_3` varchar(100) NOT NULL DEFAULT '' COMMENT '驯化属性3',
  `flush_value_4` varchar(100) NOT NULL DEFAULT '' COMMENT '驯化属性4',
  `flush_value_5` varchar(100) NOT NULL DEFAULT '' COMMENT '驯化属性5',
  `lift_exp` varchar(100) NOT NULL DEFAULT '' COMMENT '进阶经验',
  `strengthen_level` varchar(100) NOT NULL DEFAULT '' COMMENT '强化等级',
  `stuff_name` varchar(100) NOT NULL DEFAULT '' COMMENT '材料名称',
  `stuff_id` int(10) NOT NULL DEFAULT '0' COMMENT '材料ID',
  `stuff_num` int(10) NOT NULL DEFAULT '0' COMMENT '材料数量',
  `uplevel_rune_id` int(10) NOT NULL DEFAULT '0' COMMENT '完美符ID',
  `cost_coin` int(10) NOT NULL DEFAULT '0' COMMENT '铜币花费',
  `cost_gold` int(10) NOT NULL DEFAULT '0' COMMENT '自动购买元宝花费',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  `mount_data` text COMMENT '坐骑数据',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='坐骑操作日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_p2ptrade`
--

CREATE TABLE IF NOT EXISTS `log_p2ptrade` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '操作类型',
  `trade_id` varchar(100) NOT NULL DEFAULT '' COMMENT '交易订单号',
  `roleA_id` int(10) NOT NULL DEFAULT '0' COMMENT '交易人AID',
  `roleB_id` int(10) NOT NULL DEFAULT '0' COMMENT '交易人BID',
  `roleA_name` varchar(100) NOT NULL DEFAULT '' COMMENT '交易人A名',
  `roleB_name` varchar(100) NOT NULL DEFAULT '' COMMENT '交易人B名',
  `gold` int(10) NOT NULL DEFAULT '0' COMMENT '元宝',
  `total_num` int(10) NOT NULL DEFAULT '0' COMMENT '交易总数',
  `item_id1` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID1',
  `item_num1` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量1',
  `item_param1` varchar(1000) NOT NULL DEFAULT '' COMMENT '物品参数1',
  `item_id2` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID2',
  `item_num2` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量2',
  `item_param2` varchar(1000) NOT NULL DEFAULT '' COMMENT '物品参数2',
  `item_id3` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID3',
  `item_num3` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量3',
  `item_param3` varchar(1000) NOT NULL DEFAULT '' COMMENT '物品参数3',
  `item_id4` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID3',
  `item_num4` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量3',
  `item_param4` varchar(1000) NOT NULL DEFAULT '' COMMENT '物品参数3',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`),
  KEY `roleA_id` (`roleA_id`),
  KEY `roleB_id` (`roleB_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家交易日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_pet`
--

CREATE TABLE IF NOT EXISTS `log_pet` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '操作类型',
  `status` varchar(10) NOT NULL DEFAULT '' COMMENT '状态：成功或者失败',
  `pet_name` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物名称',
  `pet_id` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物ID',
  `sub_pet_id` int(10) NOT NULL DEFAULT '0' COMMENT '副宠ID',
  `level` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物等级',
  `pet_zizhi` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物资质',
  `evolve_flag` varchar(100) NOT NULL DEFAULT '' COMMENT '进化标识',
  `kuaile` varchar(100) NOT NULL DEFAULT '' COMMENT '快乐值',
  `cost_coin` int(10) NOT NULL DEFAULT '0' COMMENT '铜币花费',
  `pet_index` int(10) NOT NULL DEFAULT '0' COMMENT '索引',
  `pet_skill_id` int(10) NOT NULL DEFAULT '0' COMMENT '宠物技能ID',
  `skill_array_index` int(10) NOT NULL DEFAULT '0' COMMENT '宠物技能下标',
  `src_skill_exp` int(10) NOT NULL DEFAULT '0' COMMENT '源技能经验',
  `tar_skill_id` varchar(100) NOT NULL DEFAULT '' COMMENT '目标技能ID',
  `tar_skill_level` varchar(100) NOT NULL DEFAULT '' COMMENT '目标技能等级',
  `tar_skill_exp` varchar(100) NOT NULL DEFAULT '' COMMENT '目标技能经验',
  `pet_wuxing` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物悟性',
  `pet_wuxing_exp` varchar(100) NOT NULL DEFAULT '' COMMENT '悟性经验',
  `pet_lingxing` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物灵性',
  `refresh_index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '刷新的格子，-1为批量刷新',
  `free_refresh_times` varchar(100) NOT NULL DEFAULT '' COMMENT '免费次数',
  `lucky_value` varchar(100) NOT NULL DEFAULT '' COMMENT '幸运值',
  `gain_exp` varchar(100) NOT NULL DEFAULT '' COMMENT '获得经验',
  `cost_stuff_itemid` int(10) NOT NULL DEFAULT '0' COMMENT '物品ID',
  `cost_stuff_num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `use_rune` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否使用保护符',
  `gold` int(10) NOT NULL DEFAULT '0' COMMENT '自动购买元宝花费',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='宠物操作日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_present`
--

CREATE TABLE IF NOT EXISTS `log_present` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '表present_check的ID',
  `db_index` int(11) NOT NULL DEFAULT '0',
  `plat_user_name` varchar(100) DEFAULT NULL COMMENT '平台名',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_name` varchar(100) DEFAULT NULL,
  `coin` int(10) unsigned NOT NULL DEFAULT '0',
  `coin_bind` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定银两',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  `gold_bind` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定元宝',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品ID',
  `item_prefix_bind_attr` tinyint(4) NOT NULL DEFAULT '0' COMMENT '装备前缀绑定属性',
  `item_name` varchar(100) DEFAULT NULL COMMENT '物品名称',
  `item_num` int(11) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `item_prefix` tinyint(4) NOT NULL DEFAULT '0' COMMENT '物品前缀',
  `item_strengthen_level` int(11) NOT NULL DEFAULT '0' COMMENT '强化等级',
  `subject` varchar(100) DEFAULT NULL COMMENT '邮件标题',
  `content` text COMMENT '邮件内容',
  `reason` varchar(255) NOT NULL COMMENT '赠送原因',
  `create_time` int(10) unsigned NOT NULL COMMENT '赠送时间',
  `dead_line` int(10) unsigned NOT NULL COMMENT '过期时间',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '赠送的类型，默认0为全服赠送，1为指定玩家赠送',
  `apply_admin` varchar(100) NOT NULL COMMENT '申请者',
  `check_admin` varchar(100) NOT NULL COMMENT '审核者',
  `flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否通过审核，默认0待审核，1通过审核，2不通过审核',
  `not_pass_reason` varchar(255) NOT NULL COMMENT '未通过审核的原因',
  PRIMARY KEY (`id`),
  KEY `role_id_index` (`role_id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赠送玩家记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_present_check`
--

CREATE TABLE IF NOT EXISTS `log_present_check` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coin` int(10) unsigned NOT NULL DEFAULT '0',
  `coin_bind` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定银两',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  `gold_bind` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定元宝',
  `item_list` varchar(1000) DEFAULT NULL COMMENT '物品列表，格式：物品ID:物品数量:是否绑定;物品ID:物品数量:是否绑定',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品ID',
  `item_prefix_bind_attr` tinyint(4) NOT NULL DEFAULT '0' COMMENT '装备前缀绑定属性',
  `item_name` varchar(100) DEFAULT NULL COMMENT '物品名称',
  `item_num` int(11) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `item_prefix` tinyint(4) NOT NULL DEFAULT '0' COMMENT '物品前缀',
  `subject` varchar(100) DEFAULT NULL COMMENT '邮件标题',
  `content` text COMMENT '邮件内容',
  `reason` varchar(255) NOT NULL COMMENT '赠送原因',
  `create_time` int(10) unsigned NOT NULL COMMENT '赠送时间',
  `dead_line` int(10) unsigned NOT NULL COMMENT '过期时间',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '赠送的类型，默认0为全服赠送，1为指定玩家赠送',
  `apply_admin` varchar(100) NOT NULL COMMENT '申请者',
  `check_admin` varchar(100) NOT NULL COMMENT '审核者',
  `present_range` text COMMENT '赠送范围',
  `flag` int(4) NOT NULL DEFAULT '0' COMMENT '是否通过审核，默认0待审核，1通过审核，2不通过审核',
  `not_pass_reason` varchar(255) DEFAULT NULL COMMENT '未通过的原因',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='审核赠送记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_publicsale`
--

CREATE TABLE IF NOT EXISTS `log_publicsale` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL COMMENT '操作时间',
  `type` varchar(32) NOT NULL COMMENT '拍卖类型',
  `status` varchar(32) NOT NULL COMMENT '拍卖结果',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `role_name` varbinary(64) NOT NULL COMMENT '角色名',
  `buyer_id` int(10) NOT NULL DEFAULT '0' COMMENT '购买者ID',
  `buyer_name` varchar(100) NOT NULL DEFAULT '' COMMENT '购买者名',
  `sale_time` int(11) NOT NULL COMMENT '时间',
  `due_time` int(11) NOT NULL COMMENT '到期时间',
  `item_id` int(11) NOT NULL COMMENT '物品ID',
  `item_num` int(10) unsigned DEFAULT '0' COMMENT '物品数量',
  `coin_price` int(10) unsigned DEFAULT '0' COMMENT '该物品需要银两',
  `gold_price` int(10) unsigned DEFAULT '0' COMMENT '该物品需要元宝',
  `sale_param` varchar(255) DEFAULT '' COMMENT '拍卖参数',
  `item_param` varchar(255) DEFAULT '' COMMENT '物品参数',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品拍卖记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_register`
--

CREATE TABLE IF NOT EXISTS `log_register` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0表示玩家，1为机器人',
  `plat_user_name` varchar(100) NOT NULL COMMENT '平台帐号',
  `first_loading_time` int(10) unsigned NOT NULL COMMENT '第一次开始加载资源的时间',
  `last_loading_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次开始加载资源的时间',
  `first_loading_finish_time` int(10) unsigned NOT NULL COMMENT '第一次加载资源完成的时间',
  `last_loading_finish_time` int(10) unsigned NOT NULL COMMENT '最后一次加载资源完成的时间',
  `conn_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加载连接次数',
  `last_conn_result` tinyint(4) NOT NULL DEFAULT '0' COMMENT '最后一次连接是否成功进入游戏;0失败,1成功',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '进入创建角色界面的时间',
  `create_finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建角色完成时间',
  `first_entergame_time` int(10) NOT NULL COMMENT '第一次进入游戏的时间',
  `last_entergame_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次进入游戏的时间',
  `entergame_num` int(10) unsigned NOT NULL COMMENT '进入游戏的次数',
  `first_login_ip` varchar(50) NOT NULL COMMENT '第一次进入游戏的IP',
  `last_login_ip` varchar(50) NOT NULL COMMENT '最后一次进入游戏的IP',
  `active_user` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '进入游戏后的活跃玩家，排除第一次进入游戏后无操作的玩家',
  `user_doings_time` int(10) unsigned NOT NULL COMMENT '产生用户行为的时间',
  `load_flash_finish` varchar(50) DEFAULT NULL COMMENT '第一次加载flash的情况,加载完成为1,未加载完成为0,加载黑屏记录页面百分比',
  `click_sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在创建角色页面点击性别：1是，0否',
  `click_dice` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在创建角色页面点击骰子：1是，0否',
  `rewrite_role_name` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在创建角色页面自定义角色名：1是，0否',
  `entergame_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '在创建角色页面进入游戏的方式：1表示回车进入游戏，2表示点击确定进入游戏',
  `last_unconn_server_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后连接服务器失败的时间',
  `unconn_server_num` int(10) NOT NULL DEFAULT '0' COMMENT '连接服务器失败的次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `plat_user_name` (`plat_user_name`),
  KEY `user_type` (`user_type`),
  KEY `last_entergame_time` (`last_entergame_time`),
  KEY `first_loading_time` (`first_loading_time`),
  KEY `first_loading_time_2` (`first_loading_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='注册日志' AUTO_INCREMENT=849 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_register_result`
--

CREATE TABLE IF NOT EXISTS `log_register_result` (
  `type` int(11) NOT NULL COMMENT '3600表示每小时，86400表示每天',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '统计开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '统计结束时间',
  `all_loading_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计加载游戏的人数',
  `new_loading_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新玩家加载游戏的人数',
  `new_loading_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新玩家加载游戏的次数',
  `loading_start_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '进入加载资源界面但未加载完成的人数',
  `create_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '进入创建角色页面的人数',
  `create_unfinish_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '进入创建角色但未成功创建角色的人数',
  `create_finish_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建角色成功但未进入游戏的人数',
  `all_entergame_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计完成进入游戏的人数',
  `new_entergame_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新玩家完成进入游戏的人数',
  `yesterday_entergame_num` int(10) unsigned NOT NULL COMMENT '昨日累计完成进入游戏的人数',
  `yesterday_new_entergame_num` int(10) unsigned NOT NULL COMMENT '昨日新玩家完成进入游戏的人数，只用于86400统计',
  `yesterday_new_login_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '昨天新增玩家里，昨日登录而今天未登录的人数，只用于86400统计',
  `yesterday_login_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所有玩家里，昨日登录而今天未登录的人数',
  `all_load_flash_finish_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '第一次加载游戏并且加载完flash的人数',
  `load_flash_black` varchar(255) DEFAULT NULL COMMENT '第一次加载游戏并且加载flash出现黑屏的百分比页面',
  `user_doings_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产生用户行为的人数',
  `entergame_type_click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击进入游戏人数',
  `entergame_type_enter` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回车进入游戏人数',
  `new_conn_timeout_num` int(10) NOT NULL DEFAULT '0' COMMENT '连接服务器失败的新玩家',
  `old_ip_role_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活跃IP数',
  UNIQUE KEY `type` (`type`,`start_time`),
  KEY `type_2` (`type`,`start_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册流失率统计结果';

-- --------------------------------------------------------

--
-- 表的结构 `log_role_level`
--

CREATE TABLE IF NOT EXISTS `log_role_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `time` int(11) NOT NULL COMMENT '时间',
  `level_num` varbinary(1024) NOT NULL COMMENT '等级人数分布',
  PRIMARY KEY (`id`),
  UNIQUE KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色等级分布' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_task`
--

CREATE TABLE IF NOT EXISTS `log_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_db_index` tinyint(4) NOT NULL DEFAULT '0',
  `record_timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录log的时间',
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色名',
  `task_id` int(10) unsigned NOT NULL,
  `task_level` tinyint(4) DEFAULT NULL,
  `task_type` tinyint(4) NOT NULL,
  `role_professional` tinyint(4) DEFAULT NULL COMMENT '角色职位',
  `accept_timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接受任务的时间',
  `finish_timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '完成任务的时间',
  `quit_timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '放弃任务的时间',
  `complete_timestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '完成但未提交任务的时间',
  PRIMARY KEY (`id`),
  KEY `record_timestamp` (`record_timestamp`),
  KEY `task_type` (`task_type`),
  KEY `accept_timestamp` (`accept_timestamp`),
  KEY `finish_timestamp` (`finish_timestamp`),
  KEY `quit_timestamp` (`quit_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务数据表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_trade`
--

CREATE TABLE IF NOT EXISTS `log_trade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `op_timestamp` int(11) NOT NULL COMMENT '操作时间',
  `op_type` varchar(32) NOT NULL COMMENT '操作类型',
  `op_result` varchar(32) NOT NULL COMMENT '操作结果',
  `role_db_index` smallint(4) NOT NULL COMMENT '我方DB索引',
  `role_id` int(10) unsigned DEFAULT NULL COMMENT '我方角色ID',
  `role_name` varchar(100) DEFAULT NULL COMMENT '我方角色名',
  `item_id_1` int(10) unsigned DEFAULT NULL COMMENT '我方物品ID',
  `item_name_1` varchar(100) DEFAULT NULL COMMENT '我方物品名称',
  `item_num_1` int(10) unsigned DEFAULT NULL COMMENT '我方物品数量',
  `item_id_2` int(10) unsigned DEFAULT NULL,
  `item_name_2` varchar(100) DEFAULT NULL,
  `item_num_2` int(10) unsigned DEFAULT NULL,
  `item_id_3` int(10) unsigned DEFAULT NULL,
  `item_name_3` varchar(100) DEFAULT NULL,
  `item_num_3` int(10) unsigned DEFAULT NULL,
  `item_id_4` int(10) unsigned DEFAULT NULL,
  `item_name_4` varchar(100) DEFAULT NULL,
  `item_num_4` int(10) unsigned DEFAULT NULL,
  `item_id_5` int(10) unsigned DEFAULT NULL,
  `item_name_5` varchar(100) DEFAULT NULL,
  `item_num_5` int(10) unsigned DEFAULT NULL,
  `op_coin` int(10) unsigned DEFAULT NULL COMMENT '我方交易银两',
  `other_role_db_index` smallint(4) NOT NULL COMMENT '对方DB索引',
  `other_role_id` int(10) unsigned DEFAULT NULL COMMENT '对方角色ID',
  `other_role_name` varchar(100) DEFAULT NULL COMMENT '对方角色名称',
  `other_item_id_1` int(10) unsigned DEFAULT NULL COMMENT '对方物品ID',
  `other_item_name_1` varchar(100) DEFAULT NULL COMMENT '对方物品名称',
  `other_item_num_1` int(10) unsigned DEFAULT NULL COMMENT '对方物品数量',
  `other_item_id_2` int(10) unsigned DEFAULT NULL,
  `other_item_name_2` varchar(100) DEFAULT NULL,
  `other_item_num_2` int(10) unsigned DEFAULT NULL,
  `other_item_id_3` int(10) unsigned DEFAULT NULL,
  `other_item_name_3` varchar(100) DEFAULT NULL,
  `other_item_num_3` int(10) unsigned DEFAULT NULL,
  `other_item_id_4` int(10) unsigned DEFAULT NULL,
  `other_item_name_4` varchar(100) DEFAULT NULL,
  `other_item_num_4` int(10) unsigned DEFAULT NULL,
  `other_item_id_5` int(10) unsigned DEFAULT NULL,
  `other_item_name_5` varchar(100) DEFAULT NULL,
  `other_item_num_5` int(10) unsigned DEFAULT NULL,
  `other_op_coin` int(10) unsigned DEFAULT NULL COMMENT '对方交易银两',
  PRIMARY KEY (`id`),
  KEY `role_name` (`role_name`),
  KEY `op_timestamp` (`op_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物品交易记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_upgrade`
--

CREATE TABLE IF NOT EXISTS `log_upgrade` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '玩家昵称',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `scene_id` int(10) NOT NULL DEFAULT '0' COMMENT '场景ID',
  `scene_name` varchar(100) NOT NULL DEFAULT '' COMMENT '场景名称',
  `pos` varchar(255) NOT NULL DEFAULT '' COMMENT '坐标',
  `old_exp` bigint(20) NOT NULL DEFAULT '0' COMMENT '升级前经验',
  `add_exp` bigint(20) NOT NULL DEFAULT '0' COMMENT '升级所获经验',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '获取经验方式',
  `timestamp` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='玩家升级日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `playerfeed`
--

CREATE TABLE IF NOT EXISTS `playerfeed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plat_user_name` varchar(100) NOT NULL COMMENT '平台账号',
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(100) NOT NULL COMMENT '角色名',
  `is_vip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '默认为0不是vip，1为vip',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '反馈类型：默认0为提交bug，1为投诉，2为游戏建议，3为其他问题',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `con_type` tinyint(1) DEFAULT '0' COMMENT '玩家联系方式，0为空，1为QQ，2为电子邮件，3为电话，4为其他',
  `con_content` varchar(100) DEFAULT NULL COMMENT '玩家联系方式的具体内容',
  `occur_time` varchar(20) DEFAULT NULL COMMENT 'BUG之类的发生的时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '处理状态：默认为0未处理，1已处理',
  `op_admin` varchar(100) DEFAULT NULL COMMENT '操作管理员',
  `process` varchar(500) DEFAULT NULL COMMENT '处理办法',
  `process_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '处理时间',
  PRIMARY KEY (`id`),
  KEY `plat_user_name` (`plat_user_name`),
  KEY `role_name` (`role_name`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家反馈' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `present_card`
--

CREATE TABLE IF NOT EXISTS `present_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT '0' COMMENT '卡类型,0为新手卡',
  `card` varchar(64) NOT NULL COMMENT '卡号',
  `state` tinyint(4) DEFAULT '0' COMMENT '卡号的状态：0全新的卡、1GM导出的新卡、2使用过的卡',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成卡号的时间',
  `get_card_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取卡号的时间',
  `use_plat_user_name` varbinary(100) NOT NULL COMMENT '领取卡号的帐号',
  `role_id` int(10) unsigned NOT NULL COMMENT '使用卡号的角色ID',
  `role_name` varchar(100) NOT NULL COMMENT '使用卡号的角色',
  `use_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用卡号的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card` (`card`),
  KEY `type` (`type`,`card`,`use_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼物卡' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` smallint(4) NOT NULL DEFAULT '0' COMMENT '类别',
  `step` smallint(4) NOT NULL DEFAULT '0' COMMENT '步骤',
  `name` varchar(100) NOT NULL DEFAULT '0' COMMENT '步骤名',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，1：已完成，0：未完成',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `class` (`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营工作进度' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sendmail`
--

CREATE TABLE IF NOT EXISTS `sendmail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '邮件标题',
  `content` varchar(512) NOT NULL COMMENT '邮件内容',
  `time` int(10) NOT NULL COMMENT '发送时间',
  `role_name` text NOT NULL COMMENT '发送角色',
  `sendname` varchar(32) NOT NULL COMMENT '发送人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='GM发送邮件（不审核）' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `system`
--

CREATE TABLE IF NOT EXISTS `system` (
  `name` varchar(32) NOT NULL,
  `value` varchar(255) NOT NULL,
  `descript` varchar(255) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';
