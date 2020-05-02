<?php
/**
 * GM 操作接口
 * 返回：JSON格式
 * 引用地址示例：gm.php?role_id=11&time=22&op=xxx&p1=yyy&p2=zzz&sign=dddddddddddd
 * sign=MD5(role_id . time . op . p1 . p2 . key)
 */
require_once ('../source/global.config.inc.php');
require_once (Global_DIR.'configure/api.php');//加载API配置参数
require_once (Global_DIR.'configure/server.php');
require_once (Global_DIR.'include/functions.php'); // 加载公共函数类库

function get_role_info($role_name) 
{
	$role_info = array (
		"role_id" => 0,
		"role_name" => $role_name,
		"plat_name" => '',
	);

	$res = cls_entry::load ( 'role_name_map' )->query ( array ( 'field' => 'role_name', 'val' => $role_name ), '', '', '', '', '', 'role_id,plat_user_name' );

	if (! empty ( $res['result'] )) {
		$role_info["role_id"] = $res['result'][0]['role_id'];
		$role_info["plat_name"] = $res['result'][0]['plat_user_name'];
	}
	
	return $role_info;
}

function get_guild_info($guild_name) 
{
	$guild_info = array (
		"guild_id" => 0,
		"guild_name" => $guild_name,
	);

	$res = cls_entry::load( 'guild' )->query ( array ( 'field' => 'guild_name', 'val' => $guild_name ), '', '', '', '', '', 'guild_id' );
	if (! empty ( $res['result'] )) {
		$guild_info["guild_id"] = $res['result'][0]['guild_id'];
	}
	
	return $guild_info;
}

function get_authority_type($role_id)
{
	$res = cls_entry::load( 'role_attr_detail' )->query ( array (
			'field' => 'role_id',
			'val' => $role_id
	), '', '', '', '', '', 'authority_type' );
	
	if (empty($res['result'])) {
		return -1;
	} else {
		return $res['result'][0]['authority_type'];
	}
}

//初始化传值数组
$post = array();
$post['role_id'] = request('role_id');
$post['time'] = request('time');
$post['op'] = request('op');
$post['p1'] = request('p1');
$post['p2'] = request('p2');
$post['sign'] = request('sign');

$post['result']['code'] = 0;
$post['result']['errmsg'] = "";
$post['result']['op'] = $post['op'];
$post['result']['p1'] = $post['p1'];
$post['result']['p2'] = $post['p2'];

if (empty($post['role_id']) || empty($post['time']) || empty($post['op']) || empty($post['p1']) || empty($post['sign'])) {
	$post['result']['code'] = 1;
	$post['result']['errmsg'] = "参数错误"; 
	exit(json_encode($post['result']));
}

$my_sign = md5 ($post['role_id'] . $post['time'] . $post['op'] . $post['p1'] . $post['p2'] . constant('MD5_KEY'));
/*if ($my_sign != $post['sign']) {
	$post['result']['code'] = 2;
	$post['result']['errmsg'] = "签名错误"; 
	exit(json_encode($post['result']));
}*/

$authority_type = get_authority_type($post['role_id']);

if (2 != $authority_type) {
	$post['result']['code'] = 3;
	$post['result']['errmsg'] = "没有GM权限"; 
	exit(json_encode($post['result']));
}

$gm_info = cls_entry::load( 'role_name_map' )->query ( array (
		'field' => 'role_id',
		'val' => $post ['role_id']
), '', '', '', '', '', 'role_name,plat_user_name' );

if (empty( $gm_info['result'] )) {
	$post['result']['code'] = 4;
	$post['result']['errmsg'] = "查询错误"; 
	exit(json_encode($post['result']));
}

$post['role_name'] = $gm_info['result'][0]['role_name'];
$post['plat_name'] = $gm_info['result'][0]['plat_user_name'];


if ("mute" == $post ['op'] || "unmute" == $post ['op'] || "lock" == $post ['op'] || "unlock" == $post ['op']) {
	
	$role_info = get_role_info ( $post ['p1'] );
	if (0 == $role_info ['role_id']) {
		$post ['result'] ['code'] = 5;
		$post ['result'] ['errmsg'] = "角色不存在";
	}
	
	// 禁言
	switch ($post ['op']) {
		case 'mute':
			if (empty ( $post ['p2'] ) || $post ['p2'] <= 0 || $post ['p2'] > 31536000) {
				$post ['result'] ['code'] = 1;
				$post ['result'] ['errmsg'] = "参数错误";
				break;
			}
				
			$cmd = "Mute {$role_info['role_id']} {$post['p2']}";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 2,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 6;
				$post ['result'] ['errmsg'] = "禁言失败";
			}
			break;
		
		case 'unmute' :
			$cmd = "Mute {$role_info['role_id']} 0";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 2,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 7;
				$post ['result'] ['errmsg'] = "解禁失败";
			}
			break;
		
		case 'lock' :
			if (empty ( $post ['p2'] ) || $post ['p2'] <= 0 || $post ['p2'] > 31536000) {
				$post ['result'] ['code'] = 1;
				$post ['result'] ['errmsg'] = "参数错误";
				break;
			}

			$cmd = "Forbid {$role_info['plat_name']} {$post['p2']}";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 1,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 8;
				$post ['result'] ['errmsg'] = "封号失败";
				break;
			}
			
			$cmd = "CmdToRoleKickOut {$role_info['role_id']}";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 1,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 9;
				$post ['result'] ['errmsg'] = "踢人失败";
			}
			break;
		
		case 'unlock' :
			$cmd = "Forbid {$role_info['plat_name']} 0";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 1,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 10;
				$post ['result'] ['errmsg'] = "解封失败";
			}
			break;
	}
} else if ("clear_notice" == $post ['op'] || "close_guild" == $post ['op']) {

	$guild_info = get_guild_info ( $post ['p1'] );
	if (0 == $guild_info ['guild_id']) {
		$post ['result'] ['code'] = 11;
		$post ['result'] ['errmsg'] = "仙盟不存在";
	}
	
	// 解散仙盟
	switch ($post ['op']) {
		case 'close_guild' :
			$cmd = "CmdToGuild {$post['role_name']} {$guild_info['guild_id']} 1";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 2,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 12;
				$post ['result'] ['errmsg'] = "解散仙盟失败";
			}
			break;
		
		case 'clear_notice' :
			$cmd = "CmdToGuild {$post['role_name']} {$guild_info['guild_id']} 2";
			
			if (! cls_entry::load ( 'command' )->add ( array (
					'creator' => $post ['plat_name'],
					'createtime' => THIS_DATETIME,
					'type' => 2,
					'cmd' => $cmd 
			) )) {
				$post ['result'] ['code'] = 13;
				$post ['result'] ['errmsg'] = "清空公告失败";
			}
			break;
	}
} else {
	$post ['result'] ['code'] = 20;
	$post ['result'] ['errmsg'] = "未知操作";
}

cls_entry::load ( 'adminlog' )->addlog ( $post['result']['op'] . " " . $post['result']['p1'] . " " . $post['result']['p2'] , $post['role_id'], $post['plat_na
me'] );

exit ( json_encode ( $post ['result'] ) );
