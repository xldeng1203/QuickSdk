<?php
/*
 *注册流失率显示结果统计，600表示10分钟，3600表示每小时，86400表示每天
 *每天统计结果：crontab每个小时计算一次，数据延迟一个小时
 */

define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
require_once (Global_DIR.'configure/url.php');

$type = $argv[1];	//要统计的类型
$time = time();
switch ($type){
	case 60:
		$start_time = strtotime(date('Y-m-d H:i:00', $time-60));
	    $end_time = strtotime(date('Y-m-d H:i:00', $time));
		break;
	case 600:
		$start_time = strtotime(date('Y-m-d H:i:00', $time-600));
	    $end_time = strtotime(date('Y-m-d H:i:00', $time));
		break;
	case 3600:
		$start_time = strtotime(date('Y-m-d H:00:00', $time-300));
	    $end_time = strtotime(date('Y-m-d H:00:00', $time));
		break;
	case 86400:
		$start_time = strtotime(date('Y-m-d', $time-300));
	    $end_time = strtotime(date('Y-m-d H:00:00', $time));
		break;
}
$data = reg_lostrate($type, $start_time, $end_time);
$result = cls_entry::load('log_register_result')->add($data,true);
if($result['status'] == 0){
	exit("register_result_insert_fail");
}
exit("register_result_finish");

function reg_lostrate($type, $start_time, $end_time){
	$data['type'] = $type;
	$data['start_time'] = $start_time;
	$data['end_time'] = $end_time;

	//新玩家连接服务器超时的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'last_loading_finish_time','val'=>0,'type'=>'<='),
	         array('field'=>'last_unconn_server_time','val'=>0,'type'=>'>'),
	);
	$result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as new_conn_timeout_num');
    $res['new_conn_timeout_num'] = $result['count'];
	$data['new_conn_timeout_num'] = empty($res['new_conn_timeout_num']) ? 0 : $res['new_conn_timeout_num'];	//因为服务器超时导致流失的玩家
	//新玩家加载游戏的人数( new_loading_num )和次数( new_loading_count )
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','','','count(*) as new_loading_num, sum(conn_num) as new_loading_count');
    $res = $result['result'][0];
    $data['new_loading_num'] = empty($res['new_loading_num']) ? 0 : $res['new_loading_num'];	//新玩家加载游戏的人数
	$data['new_loading_count'] = empty($res['new_loading_count']) ? 0 : $res['new_loading_count'];	//新玩家加载游戏的次数
	
	//累计加载游戏的人数 = 新玩家人数 + 老玩家这个时间加载游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'<'),
	         array('field'=>'last_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'last_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as all_loading_num');
    $res['all_loading_num'] = $result['count'];
	$data['all_loading_num'] = $res['all_loading_num'] + $data['new_loading_num'];
	
	//新玩家进入加载资源界面但未加载完成的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'last_loading_finish_time','val'=>0,'type'=>'<='),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as loading_end_num');
    $res['loading_end_num'] = $result['count'];
	$data['loading_start_num'] = empty($res['loading_end_num']) ? 0 : $res['loading_end_num'];	//这部分是加载完成的
	
	//新玩家进入创建角色页面的人数，未必加载完成
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'create_time','val'=>0,'type'=>'>'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as create_num');
    $res['create_num'] = $result['count'];
	$data['create_num'] = empty($res['create_num']) ? 0 : $res['create_num'];
	
	//新玩家进入创建角色页面但未成功创建角色的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'create_time','val'=>0,'type'=>'>'),
	         array('field'=>'create_finish_time','val'=>0,'type'=>'<='),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as create_unfinish_num');
    $res['create_unfinish_num'] = $result['count'];
	$data['create_unfinish_num'] = empty($res['create_unfinish_num']) ? 0 : $res['create_unfinish_num'];

	//新玩家创建角色成功但未进入游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'create_time','val'=>0,'type'=>'>'),
	         array('field'=>'create_finish_time','val'=>0,'type'=>'>'),
	         array('field'=>'last_entergame_time','val'=>0,'type'=>'<='),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as create_finish_num');
    $res['create_finish_num'] = $result['count'];
	$data['create_finish_num'] = empty($res['create_finish_num']) ? 0 : $res['create_finish_num'];

	//新玩家完成进入游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	         array('field'=>'create_time','val'=>0,'type'=>'>'),
	         array('field'=>'create_finish_time','val'=>0,'type'=>'>'),
	         array('field'=>'last_entergame_time','val'=>0,'type'=>'>'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as new_entergame_num');
    $res['new_entergame_num'] = $result['count'];
	$data['new_entergame_num'] = empty($res['new_entergame_num']) ? 0 : $res['new_entergame_num'];

	//老玩家完成进入游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'<'),
	         array('field'=>'last_entergame_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'last_entergame_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','','','count(*) as old_entergame_num,count(distinct(last_login_ip)) as old_ip_role_num');
    if(!empty($result['result'])){
    	$res['old_entergame_num'] = $result['result'][0]['old_entergame_num'];
    	$res['old_ip_role_num'] = $result['result'][0]['old_ip_role_num'];
    }
    
	$old_entergame_num = empty($res['old_entergame_num']) ? 0 : $res['old_entergame_num'];
	$data['old_ip_role_num'] = empty($res['old_ip_role_num']) ? 0 : $res['old_ip_role_num'];

	//累计完成进入游戏的人数 = 新玩家进入游戏的人数 + 老玩家在这个时间进入游戏的人数
	$data['all_entergame_num'] = $data['new_entergame_num'] + $old_entergame_num;
	
	//第一次加载游戏并且加载完flash的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'load_flash_finish','val'=>1),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as all_load_flash_finish_num');
    $res['all_load_flash_finish_num'] = $result['count'];
	$data['all_load_flash_finish_num'] = empty($res['all_load_flash_finish_num']) ? 0 :$res['all_load_flash_finish_num'];
	
	//第一次加载游戏并且加载flash出现黑屏的百分比页面(所有)
	$sql = "SELECT distinct(load_flash_finish) as load_flash_finish FROM log_register WHERE user_type = 0 and load_flash_finish > 1 and first_loading_time >= '{$start_time}' and first_loading_time < '{$end_time}'";
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'load_flash_finish','val'=>1,'type'=>'>'),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','','','distinct(load_flash_finish) as load_flash_finish');
    if(!empty($result['result'])){
    	foreach ($result['result'] as $val){
    		$load_flash_black .= $val['load_flash_finish']." , ";
    	}
    }
	$data['load_flash_black'] = empty($load_flash_black) ? '' : trim($load_flash_black);
	
	//第一次加载产生用户行为的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'user_doings_time','val'=>0,'type'=>'>'),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as user_doings_num');
    $res['user_doings_num'] = $result['count'];
	$data['user_doings_num'] = empty($res['user_doings_num']) ? 0 : $res['user_doings_num'];
	
	//点击进入游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'entergame_type','val'=>2),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as entergame_type_click');
    $res['entergame_type_click'] = $result['count'];
	$data['entergame_type_click'] = empty($res['entergame_type_click']) ? 0 : $res['entergame_type_click'];
	
	//回车进入游戏的人数
	$where = array(
	         array('field'=>'user_type','val'=>0),
	         array('field'=>'entergame_type','val'=>1),
	         array('field'=>'first_loading_time','val'=>$start_time,'type'=>'>='),
	         array('field'=>'first_loading_time','val'=>$end_time,'type'=>'<'),
	);
    $result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as entergame_type_enter');
    $res['entergame_type_enter'] = $result['count'];
	$data['entergame_type_enter'] = empty($res['entergame_type_enter']) ? 0 : $res['entergame_type_enter'];

	/*
	 * 以下数据只按照天来统计
	 */
	$yesterday_start_time = $start_time - 3600*24;
	$yesterday_end_time   = $start_time;
	if($type == 86400){
		//昨日新玩家完成进入游戏的人数
		$where = array(
					array('field'=>'type','val'=>86400),
					array('field'=>'start_time','val'=>$yesterday_start_time,'type'=>'>='),
					array('field'=>'start_time','val'=>$yesterday_end_time,'type'=>'<'),
		);
		$result = cls_entry::load('log_register_result')->query($where,'','','','','','all_entergame_num as yesterday_entergame_num, new_entergame_num as yesterday_new_entergame_num');
		$res = $result['result'][0];
		$data['yesterday_new_entergame_num'] = $res['yesterday_new_entergame_num'];	//昨日新玩家完成进入游戏的人数
	    $data['yesterday_entergame_num'] 	 = $res['yesterday_entergame_num'];		//昨日累计进入游戏的人数
	    
	    //昨天新玩家里，昨日登录而今天未登录的人数
	    $where = array(
					array('field'=>'user_type','val'=>0),
					array('field'=>'first_loading_time','val'=>$yesterday_start_time,'type'=>'>='),
					array('field'=>'first_loading_time','val'=>$yesterday_end_time,'type'=>'<'),
					array('field'=>'last_entergame_time','val'=>$yesterday_start_time,'type'=>'>='),
					array('field'=>'last_entergame_time','val'=>$yesterday_end_time,'type'=>'<'),
		);
		$result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as yesterday_new_login_num');
		$res['yesterday_new_login_num'] = $result['count'];
		$data['yesterday_new_login_num'] = $res['yesterday_new_login_num'];
		
		//所有玩家里，昨日登录而今天未登录的人数
		 $where = array(
					array('field'=>'user_type','val'=>0),
					array('field'=>'last_entergame_time','val'=>$yesterday_start_time,'type'=>'>='),
					array('field'=>'last_entergame_time','val'=>$yesterday_end_time,'type'=>'<'),
		);
		$result = cls_entry::load('log_register')->query($where,'','','','',true,'count(*) as yesterday_login_num');
		$res['yesterday_login_num'] = $result['count'];
		$data['yesterday_login_num'] = $res['yesterday_login_num'];
	}else{
		$data['yesterday_new_entergame_num'] = 0;	//昨日新玩家完成进入游戏的人数
	    $data['yesterday_entergame_num'] = 0;		//昨日累计进入游戏的人数
	    $data['yesterday_new_login_num'] = 0;
	    $data['yesterday_login_num'] = 0;
	}
	return $data;
}
?>