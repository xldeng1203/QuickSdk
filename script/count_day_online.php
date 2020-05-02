<?php
/**
 * 获取每个小时人数最高的记录
 * 定时任务，一个小时执行一次
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
include_once(ADMIN_DIR.'/source/config.inc.php');
require_once (CLS_DIR.'greplog.class.php');


/**
 * 正点执行任务，匹配前一个小时生成的所有在线日志，获取到其中人数最高的记录写入数据库
 * @var unknown_type
 */
//当前时间
$time = $_SERVER['REQUEST_TIME'];
//一个小时之前
$time_before = $time - 3600;
//匹配到小时
$now = date('Y-m-d H',$time_before);
$source_log = $g_c["log"]["api_curr_online"];
//$data返回匹配的内容
$res = GrepLog::grep($source_log,$now,$data);

//对于获取到的内容进行比较过滤
if($res){
	$online_high = 0;
	$online_time = 0;
	foreach($data as $value){
		preg_match('/\[(.*?)\].+:.*\[(\d+)\/(\d+)\]/i',$value,$output);
/*		$timeindex = strtotime($output[1]);
		$online[$timeindex]['time'] = $output[1];		//时间
		$online[$timeindex]['line'] = "$output[2]";	//进程是否走完产生的数据
		$online[$timeindex]['total_role'] = $output[3];	//当前在线人数
		$online[$timeindex]['thread_num'] = $output[4];*/	//服务器总人数
		if($output[2] >= $online_high){
			$online_high = $output[2];
			$online_time = $output[1];
		}
	}
	$type = 3600;
	$ipost = array();
	$ipost = array(
		'timestamp' =>strtotime($online_time),
		'type' => $type,
		'data' => $online_high,
	);
	//将获取到的内容插入到log_count_online表中
	$insertid = cls_entry::load('log_count_online')->add($ipost);
	exit($insertid);
}
