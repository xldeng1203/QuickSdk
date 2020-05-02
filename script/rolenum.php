<?php
/*
 * 定时赋予玩家登录产生的log可读的权限
 * 根据时间判断是否执行计划任务
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
require_once (Global_DIR.'configure/url.php');

$serverlog_dir = $g_c["serverlog_dir"];
$strcmd = "chmod +r {$serverlog_dir}gamelog_0/rolenum*"; //赋予日志文件读的权限
exec($strcmd);
echo "chmod file limits OK!\n";

//================================================
//========= 根据时间判断执行特定定时任务 ==============
//================================================
$time = $_SERVER['REQUEST_TIME'];
$i = date('i',$time);    //分钟
$h = date('H:i',$time);  //小时+分钟

/**
 * 数据清理定时任务，每小时25分的时候执行一次，执行删除15天之前的日志数据
 */
if($i == '25'){
	//随机错开时间
	sleep(rand(0,180));
	//需要清理的日志表，只需要保留15天的日志表，每次删除50000条记录
	$tables = array(
		array('timestamp','log_battlefield'),
		array('timestamp','log_chestshop'),
		array('timestamp','log_disconnect'),    
		array('timestamp','log_drop'),          
		array('timestamp','log_equipment'),
		array('timestamp','log_functionstats'),
		array('trade_timestamp','log_gold_shop'),
		array('timestamp','log_item'),
		array('timestamp','log_jingjie'),
		array('op_timestamp','log_knapsack'),
		//array('timestamp','log_login'),
		array('timestamp','log_mail'),
		array('op_timestamp','log_marry'),
		array('op_timestamp','log_money_coin'),
		array('timestamp','log_mount'),
		array('timestamp','log_p2ptrade'),
		array('timestamp','log_pet'),
		array('timestamp','log_publicsale'),
		array('op_timestamp','log_trade'),
		array('timestamp','log_upgrade'),
		//array('op_timestamp','log_money_gold'),
	);
	foreach($tables as $table){
		//元宝使用记录表保留30天记录
		if($table[1] == 'log_money_gold'){
			$endtime = $time - 30*60*60*24;
		}else{
			$endtime = $time - 15*60*60*24;
		}
		$sql = "DELETE FROM {$table[1]} WHERE {$table[0]} < $endtime LIMIT 50000";
		cls_entry::load('log_battlefield')->execute($sql);
	}
}

/**
 * 统计玩家在线时长，每天凌晨1:20执行
 */
if($h == '01:25'){
	//昨天有登录的玩家
	$time = strtotime('midnight')-86400;
	$arrs = array(
		'all','charge_all','charge_100','charge_500','charge_1000','charge_2000','charge_5000','charge_more'
	);
	
	foreach($arrs as $arr){
		$post = array();
		$post['count_type'] = $arr;
		$post['timestamp'] = $time;
		$post['total'] = getOnlineTime($arr);   //总数
		$post['tenmin'] = getOnlineTime($arr,'',10*60);  //在线时长大于10分钟
		$post['thirtymin'] = getOnlineTime($arr,10*60,30*60);  //在线时长在10-30分钟之间
		$post['onehour'] = getOnlineTime($arr,30*60,1*60*60);  //在线时长在30分钟到1小时之间
		$post['threehour'] = getOnlineTime($arr,1*60*60,3*60*60);  //在线时长在1-3小时之间
		$post['fivehour'] = getOnlineTime($arr,3*60*60,5*60*60);   //在线时长在3-5小时之间
		$post['sevenhour'] = getOnlineTime($arr,5*60*60,7*60*60);  //在线时长在5-7小时之间
		$post['moresevenhour'] = getOnlineTime($arr,7*60*60,'');   //在线时长在7小时以上
		
		cls_entry::load('count_online_time')->add($post);
	}
}

function getOnlineTime($type='all',$low='',$high=''){
	$where = array();
	$st = strtotime('midnight')-86400;
	$where[] = array('field'=>'last_save_time','val'=>$st,'type'=>'>');
	if(!empty($low)){
		$where[] = array('field'=>'lastday_online_time','val'=>$low,'type'=>'>=');
	}else{
		$where[] = array('field'=>'lastday_online_time','val'=>0,'type'=>'>');
	}
	if(!empty($high)){
		$where[] = array('field'=>'lastday_online_time','val'=>$high,'type'=>'<');
	}
	
	$roleids = 0;
	switch ($type){
		case 'charge_all':
			$roleids = getChargeOnlineTime();
			break;
		case 'charge_100':
			$roleids = getChargeOnlineTime('',100);   //充值金额在100一下
			break;
		case 'charge_500':
			$roleids = getChargeOnlineTime(100,500);  //充值金额在100-500之间
			break;
		case 'charge_1000':
			$roleids = getChargeOnlineTime(500,1000);  //充值金额在500-1000之间
			break;
		case 'charge_2000':
			$roleids = getChargeOnlineTime(1000,2000);  //充值金额在1000-2000之间
			break;
		case 'charge_5000':
			$roleids = getChargeOnlineTime(2000,5000);  //充值金额在2000-5000之间
			break;
		case 'charge_more':
			$roleids = getChargeOnlineTime(5000,'');   //充值金额在5000以上
			break;																				
	}
	if(empty($roleids) && $type != 'all'){
		$rt = 0;
		return $rt;
	}elseif(!empty($roleids)){
		$where[] = array('field'=>'role_attr_detail.role_id','val'=>$roleids,'type'=>'IN');
	}
	$join = array('table'=>'role','type'=>'LEFT','left'=>'role.role_id','right'=>'role_attr_detail.role_id');
	$result = cls_entry::load('role_attr_detail')->query($where,'',$join,'','',true);
	if(!empty($result['result'])){
		$rt = $result['result'];
	}else{
		$rt = 0;
	}
	return $rt;
}

function getChargeOnlineTime($low='',$high=''){
	$roleid_arr = array();
	$roleids = '';
	$where = '';
	$where = " WHERE 1 GROUP BY role_id HAVING ";
	if(!empty($low) && !empty($high)) {
		$where .= " money >= $low AND money < $high " ;
	} else if(!empty($low)){
		$where .= " money >= $low ";
	} else if(!empty($high)){
		$where .= " money < $high ";
	} else {
		$where .= " money > 0 "; 
	}
	$field = " SUM(money) AS money,role_id ";
	$charge = cls_entry::load('charge')->query7($where,'','','','','',$field);
	if(!empty($charge['result'])){
		foreach($charge['result'] as $val){
			$roleid_arr[] = $val['role_id'];
		}
	}
	if(!empty($roleid_arr)){
		$roleids = implode(',', $roleid_arr);
	}
	return $roleids;
}

exit("EXEC OK!\n");
?>