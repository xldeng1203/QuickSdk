<?php
/**
 * 获取玩家留存率
 * 定时任务，一天执行一次
 * 每天1:10分执行前一天的数据统计
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
include_once(ADMIN_DIR.'/source/config.inc.php');

//错误时间
sleep(rand(0, 180));

//获取当前时间
$time = date('Y-m-d 00:00:00');
//$time = "2014-02-25 00:00:00";
$timestamp = strtotime($time);
$st = $timestamp-86400;
$et = $timestamp;

$join = array('table'=>'role as b','type'=>'as a LEFT','left'=>'a.role_id_1','right'=>'b.role_id');

//统计昨天的数据
$where = " WHERE createtime >= $st AND createtime< $et AND role_id_1>0 ";
$result = cls_entry::load('login')->query($where,'','','','',true);
if(!empty($result['result'])){
	$post['total'] = $result['result'];
}
$post['timestamp'] = $st;

cls_entry::load('count_player_live')->add($post);
unset($post);


//更新前天的数据
$st_2 = $timestamp-86400*2;
$et_2 = $timestamp-86400;
$where = " WHERE a.createtime>=$st_2 AND a.createtime<$et_2 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login2'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_2),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_2));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_2 AND createtime<$et_2 AND role_id_1>0 ",'','','','',true);
		$post['login2'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_2;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);


//更新3天前数据
$st_3 = $timestamp-86400*3;
$et_3 = $timestamp-86400*2;
$where = " WHERE a.createtime>=$st_3 AND a.createtime<$et_3 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login3'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_3),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_3));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_3 AND createtime<$et_3 AND role_id_1>0 ",'','','','',true);
		$post['login3'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_3;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新4天前数据
$st_4 = $timestamp-86400*4;
$et_4 = $timestamp-86400*3;
$where = " WHERE a.createtime>=$st_4 AND a.createtime<$et_4 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login4'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_4),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_4));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_4 AND createtime<$et_4 AND role_id_1>0 ",'','','','',true);
		$post['login4'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_4;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新5天前数据
$st_5 = $timestamp-86400*5;
$et_5 = $timestamp-86400*4;
$where = " WHERE a.createtime>=$st_5 AND a.createtime<$et_5 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login5'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_5),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_5));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_5 AND createtime<$et_5 AND role_id_1>0 ",'','','','',true);
		$post['login5'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_5;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新6天前数据
$st_6 = $timestamp-86400*6;
$et_6 = $timestamp-86400*5;
$where = " WHERE a.createtime>=$st_6 AND a.createtime<$et_6 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login6'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_6),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_6));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_6 AND createtime<$et_6 AND role_id_1>0 ",'','','','',true);
		$post['login6'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_6;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新7天前数据
$st_7 = $timestamp-86400*7;
$et_7 = $timestamp-86400*6;
$where = " WHERE a.createtime>=$st_7 AND a.createtime<$et_7 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login7'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_7),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_7));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_7 AND createtime<$et_7 AND role_id_1>0 ",'','','','',true);
		$post['login7'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_7;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新8天前数据
$st_8 = $timestamp-86400*8;
$et_8 = $timestamp-86400*7;
$where = " WHERE a.createtime>=$st_7 AND a.createtime<$et_7 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login8'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_8),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_8));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_8 AND createtime<$et_8 AND role_id_1>0 ",'','','','',true);
		$post['login8'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_8;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新15天前数据
$st_15 = $timestamp-86400*15;
$et_15 = $timestamp-86400*14;
$where = " WHERE a.createtime>=$st_15 AND a.createtime<$et_15 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login15'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_15),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_15));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_15 AND createtime<$et_15 AND role_id_1>0 ",'','','','',true);
		$post['login15'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_15;
		cls_entry::load('count_player_live')->add($post);
	}
}
unset($upost);
unset($post);

//更新30天前数据
$st_30 = $timestamp-86400*30;
$et_30 = $timestamp-86400*29;
$where = " WHERE a.createtime>=$st_30 AND a.createtime<$et_30 AND (a.lastlogintime>=$st OR b.last_save_time>=$st)";
$result = cls_entry::load('login')->query($where,'',$join,'','',true);
if(!empty($result['result'])){
	$upost['login30'] = $result['result'];
	$res = cls_entry::load('count_player_live')->query(array('field'=>'timestamp','val'=>$st_30),'','','','',true);
	if(!empty($res['result'])){
		cls_entry::load('count_player_live')->updateinfo($upost,array('field'=>'timestamp','val'=>$st_30));
	}else{
		$r = cls_entry::load('login')->query(" WHERE createtime>=$st_30 AND createtime<$et_30 AND role_id_1>0 ",'','','','',true);
		$post['login30'] = $result['result'];
		$post['total']  = $r['result'];
		$post['timestamp'] = $st_30;
		cls_entry::load('count_player_live')->add($post);
	}
}


exit('OK!');



