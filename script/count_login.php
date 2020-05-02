<?php 
/**
 * 登录统计，每个小时统计一次
 * 每小时02分的时候执行统计上一个小时的登录记录
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');

$st = strtotime(date('Y-m-d H:00:00',strtotime('-1 hour')));
$et = strtotime(date('Y-m-d H:59:59',strtotime('-1 hour')));

$where[] = array('field'=>'timestamp','val'=>$st,'type'=>'>=');
$where[] = array('field'=>'timestamp','val'=>$et,'type'=>'<=');
$where[] = array('field'=>'type','val'=>'OnGlobalUserLogin');

$field = ' count(*) AS times,count(distinct(`role_id`)) AS roles,count(distinct(`ip`)) AS ips ';

$result = cls_entry::load('log_login')->query($where,'','','','','',$field);
if(!empty($result['result'])){
	$res = $result['result'];
	$post['timestamp'] = $st;
	$post['times']     = $res[0]['times'];
	$post['roles']     = $res[0]['roles'];
	$post['ips']       = $res[0]['ips'];
	
	//获取充值玩家列表
	$chargeWhere = " WHERE `mode` != 'admin' AND `result` = 8 ";
	$chargeField = " DISTINCT(`role_id`) ";
	$chargeRes = cls_entry::load('charge')->query($chargeWhere,'','','','','',$chargeField);
	if(!empty($chargeRes['result'])){
		foreach($chargeRes['result'] as $c){
			$rs[] = $c['role_id']; 
		} 
		$chargeRoles = join(',',$rs);
	}else{
		$chargeRoles = '';
	}
	
	//查询充值玩家登录情况
	if(!empty($chargeRoles)){
		$where[] = array('field'=>'role_id','val'=>$chargeRoles,'type'=>'IN');
		$field = ' count(distinct(`role_id`)) AS pays ';
		$charge = cls_entry::load('log_login')->query($where,'','','','','',$field);
		if(!empty($charge['result'])){
			$res = $charge['result'];
			$post['pays']  = $res[0]['pays'];
			$post['nopays']= $post['roles'] - $res[0]['pays'];
		}else{
			$post['pays']  = 0;
			$post['nopays']= $post['roles'];
		}		
	}else{
		$post['pays'] = 0;
		$post['nopays'] = $post['roles'];
	}

}else{
	$post['timestamp'] = $st;
	$post['times']     = 0;
	$post['roles']     = 0;
	$post['ips']       = 0;
	$post['pays']      = 0;
	$post['nopays']    = 0;	
}

//插入数据库中
$insert = cls_entry::load('count_login')->add($post);
if($insert){
	exit('OK');
}

?>