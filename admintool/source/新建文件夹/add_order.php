<?php
/**
 * 后台补单给玩家充值
 */
require_once ('config.inc.php');

$action = request ( 'ac' );
switch ($action) {
	case 'add' :
		$user = urldecode ( request ( 'user' ) );
		$server_id = request ( 'server_id' );
		$gold = request ( 'gold' );
		$role_id = request ( 'role_id' );
		$paynum = request ( 'paynum' );
		$role_name = urldecode ( request ( 'role_name' ) );
		
		if (! empty ( $user ) && isset ( $server_id ) && ! empty ( $gold )) {
			$plat_user_name = $user . '_' . $server_id;
			
			// 插入命令，优化充值后到账时间
			if (! cls_entry::load ( 'accountgold' )->add_gold ( $plat_user_name, $gold )) {
				alert ( '补单失败!', 'add_order.php' );
				exit(1);
			}

			$pcommand = array (
					'creator' => 'background', // 后台
					'createtime' => THIS_DATETIME,
					'type' => 2, // loginserver
					'cmd' => "newnotice 3 {$role_id}" 
			);
			$cinsert = cls_entry::load ( 'command' )->add ( $pcommand );
			$log = "{$userInfo['username']}，对玩家{$plat_user_name}进行补单操作；订单号：{$paynum}；角色名：{$role_name}；元宝数量：{$gold}";
			cls_entry::load ( 'adminlog' )->addlog ( $log );
			alert ( '补单成功！', 'add_order.php' );
		}
		break;
}

$where = array ();

$paynum = request ( 'paynum' );
if (! empty ( $paynum )) {
	$where [] = array (
			'field' => 'paynum',
			'val' => $paynum 
	);
	$result = cls_entry::load ( 'charge' )->query ( $where );
	if (! empty ( $result ['result'] )) {
		$chargelist = $result ['result'] [0];
	} else {
		alert ( '找不到该订单', 'add_order.php' );
	}
}

$smarty->assign ( "chargelist", $chargelist );
$smarty->display ( "add_order.shtml" );
?>