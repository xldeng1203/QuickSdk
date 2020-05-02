<?php
/**
 * 元宝/绑定元宝/铜钱库存统计 - 数据入库接口（计划任务）
 * 获取前一天的数据写入数据库  admintool.count_store
 * @author JustFantasy
 * 2012.10.24
 */
define ( 'SCRIPT', dirname ( __FILE__ ) );
define ( 'ADMIN_DIR', str_replace ( 'script', 'admintool', SCRIPT ) );
include_once (ADMIN_DIR . '/source/global.config.inc.php');

// 每天凌晨00:01执行数据统计前一天的数据
$et = strtotime ( 'midnight' );
$st = $et - 86400;

// 获取内部玩家，过滤内部玩家
$result = cls_entry::load ( 'charge' )->get_admin_roleid ();
if (! empty ( $result ['result'] )) {
	$adminroleid = join ( ',', $result ['result'] );
} else {
	$adminroleid = '';
}

// 获取当日充值元宝总量，已过滤内部玩家后台充值 mode != 'admin'
$post ['charge_gold'] = 0;
$charge = cls_entry::load ( 'charge' )->gettotal ( $st, $et, 'SUM(`gold`) AS totalgold' );
if (! empty ( $charge ['result'] )) {
	$post ['charge_gold'] = $charge ['result'] ['totalgold'];
}

// 获取后台充值给内部玩家元宝数
$post ['gm_gold'] = 0;
$where = " WHERE mode='admin' AND result=8 AND time>=$st AND time<=$et ";
$field = " SUM(gold) AS gold ";
$charge = cls_entry::load ( 'charge' )->query7 ( $where, '', '', '', '', '', $field );
if (! empty ( $charge ['result'] )) {
	$post ['gm_gold'] = $charge ['result'] [0] ['gold'];
}

// 后台邮件发送元宝/绑定元宝
$post ['back_gold'] = 0;
$post ['back_goldbind'] = 0;
$back = cls_entry::load ( 'present' )->gettotal ( $st, $et, 'SUM(`gold`) AS totalgold,SUM(`gold_bind`) AS totalgoldbind', $adminroleid );
if (! empty ( $back ['result'] )) {
	$post ['back_gold'] = $back ['result'] ['totalgold'];
	$post ['back_goldbind'] = $back ['result'] ['totalgoldbind'];
}

// 当日总消耗元宝/绑定元宝,增加绑定元宝
$post ['consume_goldbind'] = 0;
$post ['add_goldbind'] = 0;
$consume = cls_entry::load ( 'log_money_gold' )->getTotal ( $st, $et, 'SUM(`use_gold_bind`) AS totalgoldbind,SUM(`add_gold_bind`) AS totalGoldBindAdd', $adminroleid );
if (! empty ( $consume ['result'] )) {
	$post ['consume_goldbind'] = $consume ['result'] ['totalgoldbind'];
	$post ['add_goldbind'] = $consume ['result'] ['totalGoldBindAdd'];
}

// 玩家祈福元宝消耗
$post ['player_chestshop_consume'] = 0;
$in = $adminroleid ? " AND role_id NOT IN ( $adminroleid ) " : "";
$where = " WHERE use_for='ChestShopBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
$field = " SUM(use_gold) AS gold ";
$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
if (! empty ( $consume ['result'] )) {
	$post ['player_chestshop_consume'] = $consume ['result'] [0] ['gold'];
}

// 内玩祈福元宝消耗
$post ['gm_chestshop_consume'] = 0;
if (! empty ( $adminroleid )) {
	$in = $adminroleid ? " AND role_id IN ( $adminroleid ) " : "";
	$where = " WHERE use_for='ChestShopBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
	$field = " SUM(use_gold) AS gold ";
	$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
	if (! empty ( $consume ['result'] )) {
		$post ['gm_chestshop_consume'] = $consume ['result'] [0] ['gold'];
	}
}

// 玩家商城元宝消耗
$post ['play_shop_consume'] = 0;
$in = $adminroleid ? " AND role_id NOT IN ( $adminroleid ) " : "";
$where = " WHERE use_for='ShopBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
$field = " SUM(use_gold) AS gold ";
$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
if (! empty ( $consume ['result'] )) {
	$post ['play_shop_consume'] = $consume ['result'] [0] ['gold'];
}

// 内玩商城元宝消耗
$post ['gm_shop_consume'] = 0;
if (! empty ( $adminroleid )) {
	$in = $adminroleid ? " AND role_id IN ( $adminroleid ) " : "";
	$where = " WHERE use_for='ShopBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
	$field = " SUM(use_gold) AS gold ";
	$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
	if (! empty ( $consume ['result'] )) {
		$post ['gm_shop_consume'] = $consume ['result'] [0] ['gold'];
	}
}

// 玩家其它元宝消耗
$post ['consume_gold'] = 0;
$in = $adminroleid ? " AND role_id NOT IN ( $adminroleid ) " : "";
$where = " WHERE use_for NOT IN ('ShopBuy','TradeOut','PublicSaleBuy','ChestShopBuy') AND op_timestamp>=$st AND op_timestamp<=$et $in ";
$field = " SUM(use_gold) AS gold ";
$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
if (! empty ( $consume ['result'] )) {
	$post ['consume_gold'] = $consume ['result'] [0] ['gold'];
}

// 内玩其它元宝消耗
$post ['gm_other_consume'] = 0;
if (! empty ( $adminroleid )) {
	$in = $adminroleid ? " AND role_id IN ( $adminroleid ) " : "";
	$where = " WHERE use_for NOT IN ('ShopBuy','TradeOut','PublicSaleBuy','ChestShopBuy') AND op_timestamp>=$st AND op_timestamp<=$et $in ";
	$field = " SUM(use_gold) AS gold ";
	$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
	if (! empty ( $consume ['result'] )) {
		$post ['gm_other_consume'] = $consume ['result'] [0] ['gold'];
	}
}

// 玩家市场元宝消耗
$post ['play_market'] = 0;
$in = $adminroleid ? " AND role_id NOT IN ( $adminroleid ) " : "";
$where = " WHERE use_for='PublicSaleBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
$field = " SUM(use_gold) AS gold ";
$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
if (! empty ( $consume ['result'] )) {
	$post ['play_market'] = $consume ['result'] [0] ['gold'];
}

// 内玩市场消耗
$post ['gm_market'] = 0;
if (! empty ( $adminroleid )) {
	$in = $adminroleid ? " AND role_id IN ( $adminroleid ) " : "";
	$where = " WHERE use_for='PublicSaleBuy' AND op_timestamp>=$st AND op_timestamp<=$et $in ";
	$field = " SUM(use_gold) AS gold ";
	$consume = cls_entry::load ( 'log_money_gold' )->query7 ( $where, '', '', '', '', '', $field );
	if (! empty ( $consume ['result'] )) {
		$post ['gm_market'] = $consume ['result'] [0] ['gold'];
	}
}

// 当日增加/消耗绑定铜币
$post ['add_coinbind'] = 0;
$post ['consume_coinbind'] = 0;
$field = 'SUM(`use_coin_bind`) AS totalcoinbind,SUM(`add_coin_bind`) AS totalCoinBindAdd';
$coinbind = cls_entry::load ( 'log_money_coin' )->getTotal ( $st, $et, $field, $adminroleid );
if (! empty ( $coinbind ['result'] )) {
	$post ['add_coinbind'] = $coinbind ['result'] ['totalCoinBindAdd'];
	$post ['consume_coinbind'] = $coinbind ['result'] ['totalcoinbind'];
}

// 当日增加/消耗非绑定铜币
$post ['add_coin'] = 0;
$post ['consume_coin'] = 0;

// 内玩元宝库存
$post ['gm_store_gold'] = 0;
if (! empty ( $adminroleid )) {
	$where = " WHERE role_id IN ( $adminroleid ) ";
	$field = " SUM(gold) AS gold ";
	$player = cls_entry::load ( 'role_attr_detail' )->query7 ( $where, '', '', '', '', '', $field );
	if (! empty ( $player ['result'] )) {
		$post ['gm_store_gold'] = $player ['result'] [0] ['gold'];
	}
}

// 元宝/绑定元宝/铜币库存总量（截止当天）
$post ['store_gold'] = 0;
$post ['store_goldbind'] = 0;
$post ['store_coin'] = 0;
$post ['store_coinbind'] = 0;
$player = cls_entry::load ( 'role_attr_detail' )->getTotal ( '', '', $adminroleid );
if (! empty ( $player ['result'] )) {
	$post ['store_gold'] = $player ['result'] ['totalGold'];
	$post ['store_goldbind'] = $player ['result'] ['totalGoldBind'];
	$post ['store_coinbind'] = $player ['result'] ['totalCoinBind'];
}

// 元宝/绑定元宝/铜币库存总量（3天登录）
$time_3 = $et - 86400 * 3;
$field_3 = 'SUM(`gold`) AS totalGold,SUM(`gold_bind`) AS totalGoldBind';
$post ['store_gold_3'] = 0;
$post ['store_goldbind_3'] = 0;
$player_3 = cls_entry::load ( 'role_attr_detail' )->getTotal ( $time_3, $field_3, $adminroleid );
if (! empty ( $player ['result'] )) {
	$post ['store_gold_3'] = $player_3 ['result'] ['totalGold'];
	$post ['store_goldbind_3'] = $player_3 ['result'] ['totalGoldBind'];
}

// 元宝/绑定元宝/铜币库存总量（7天登录）
$time_7 = $et - 86400 * 7;
$field_7 = 'SUM(`gold`) AS totalGold,SUM(`gold_bind`) AS totalGoldBind';
$post ['store_gold_7'] = 0;
$post ['store_goldbind_7'] = 0;
$player_7 = cls_entry::load ( 'role_attr_detail' )->getTotal ( $time_7, $field_7, $adminroleid );
if (! empty ( $player ['result'] )) {
	$post ['store_gold_7'] = $player_7 ['result'] ['totalGold'];
	$post ['store_goldbind_7'] = $player_7 ['result'] ['totalGoldBind'];
}

// 时间
$post ['time'] = $st;
$insert = cls_entry::load ( 'count_store' )->add ( $post );
$insertid = ! empty ( $insert ['result'] ) ? $insert ['result'] : 0;
exit ( $insertid );
?>