<?php
/*
 *用户在线统计 - 数据入库接口（计划任务）
 *目前只获取 5分钟、 15分钟、一个小时、一天的数据插入到表 admintool.log_count_online
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
/*if(count($argv)<2){ //$argv 返回命令行的参数： 900 或 3600 或 86400
	exit("使用格式例如：php count_online.php 3600");
}else{
	$interval_time = $type = $argv[1];
}*/
//一天获取一次数据，将每天获取到的数据写入数据库
//每天的23:59分执行一次
$time = $_SERVER['REQUEST_TIME'];
$now_time = strtotime(date('Y-m-d',$time));

$begin_time = $now_time;
$end_time = $now_time + 86400;

$where = array(
           array('field'=>'last_save_time','val'=>$begin_time,'type'=>'>'),
           array('field'=>'last_save_time','val'=>$end_time,'type'=>'<='),
           array('field'=>'create_time','val'=>0,'type'=>'>'),
           array('field'=>'role_id','val'=>0,'type'=>'>'),
);

$wheres = array(
           array('field'=>'timestamp','val'=>$begin_time,'type'=>'>'),
           array('field'=>'timestamp','val'=>$end_time,'type'=>'<='),
);
$wheres[] = array('field'=>'type','val'=>3600);
//获取当天登陆的玩家总数
$result = cls_entry::load('role')->query($where,'','','','',true);
if($result['status']){
	$total = $result['count'];
}
//获取最高在线数和平均数
$res = cls_entry::load('log_count_online')->query($wheres);
$high = 0;
$avgs = 0;
$avg  = 0;
$index = 0;
if(!empty($res['result'])){
	foreach($res['result'] as $k=>$v){
		if($v['data'] > $high){
			$high = $v['data'];
		}
		$avgs += $v['data']; 
		$index++;
	}
	$avg = round( $avgs / $index );
}
$type = 86400;
$ipost = array();
$ipost = array(
	'timestamp' => $now_time,
	'type' => $type,
	'data' => 0,
	'total' => $total,
	'high' => $high,
	'avg' => $avg,
);
$insertid = cls_entry::load('log_count_online')->add($ipost);
exit($insertid);
?>