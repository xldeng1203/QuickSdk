<?php
/**
 * 命令处理(充值元宝命令)
 */
require_once('config.inc.php');
$action = request('ac');

switch ($action){
	case "add":
		$post['creator'] = $userInfo['username'].'【'.trim(request('creator').'】' );
		$post['createtime'] = time();
		$post['type'] =  request('type');
		$payval =  request('payval');
		$role_id = request('role_id');
		$role_name = request('role_name');
		//从role_name_map库中查询订单信息
		$res = cls_entry::load('role_name_map')->query(array('field'=>'role_id','val'=>$role_id),'','','','','','db_index');
		if($res['status']){
			$db_index = $res['result'][0]['db_index'];
		}
		$post['cmd'] = "CmdToRole {$db_index} {$role_id} 2 {$payval}";
		//向command库中插入一条数据
		$result = cls_entry::load('command')->add($post);
		if($result['status']){
			$log = "{$_LANG['option']['add']}{$_LANG['setvip']['setvip']}：[{$_LANG['admin']['create']}：".$post['creator'].",{$_LANG['info']['roleid']}：$role_id,{$_LANG['info']['rolename']}:$role_name,{$_LANG['info']['gold']}:$payval]";
			//失败，没有adminlog数据库表
			cls_entry::load('adminlog')->addlog($log);
			alert("{$_LANG['option']['add']}{$_LANG['system']['success']}！", "command_charge.php");
		}else{
			alert("{$_LANG['option']['add']}{$_LANG['system']['fail']}！", "command_charge.php");
		}
		break;
	default:
		$num = 15;
		break;
}

$where = array();
$page = request('page') > 0 ? request('page') : 1 ;
$pagesize = request('num') > 0 ? request('num') : $num ;
$query = "?num=$pagesize";

$type = 'CmdToRole';
//从command库中查询数据
$result = cls_entry::load('command')->getlistbytype($type,$page,$pagesize);
if(!empty($result['result'])){
	$cmdlist = array();
	$roles = array();
	foreach ($result['result'] as $key => $val){
		$cmdarr = explode(' ',$val['cmd']);
		if($cmdarr[3] != 2) continue;
		$cmdlist[$key]['db_index'] = $cmdarr[1];
		$cmdlist[$key]['role_id'] = $cmdarr[2];
		$cmdlist[$key]['gold'] = $cmdarr[4];

		$cmdlist[$key]['creator'] = $val['creator'];
		$cmdlist[$key]['createtime'] = $val['createtime'];
		$cmdlist[$key]['type'] = $val['type'];
		$cmdlist[$key]['cmd'] = $cmdarr[0] == $type ? "{$_LANG['setvip']['detail']}" : "{$_LANG['command']['not']}";
		$cmdlist[$key]['confirmtime'] = $val['confirmtime'];
	}

}
$displaynum = displayNum('command_charge.php',request('num'));
$smarty->assign('displaynum',$displaynum);
$smarty->assign('cmdlist',$cmdlist);
$smarty->display("command_charge.shtml");
?>