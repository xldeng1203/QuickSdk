<?php
/**
 * 管理员给玩家充值
 */
require_once('config.inc.php');

$action = request('ac');
switch ($action){
	case "charge":
		//解析获取玩家真实的平台账号和服ID
		$plats  = request('plat_user_name');
		$pArr   = explode('_', $plats);
		$sid    = $pArr[count($pArr)-1];
		unset($pArr[count($pArr)-1]);
		$user   = implode('_', $pArr);
		$time	= time();
		$paynum = 'admin_'.date('YmdHis', $time).mt_rand(0,4); //订单号
		$mode 	= 'admin';
		$money 	= 0;
		$gold 	= intval(request('gold')); //充值的元宝
		$key    = PAYKEY;
		$ticket = md5( $paynum . $user . $money . $gold . $time . $key); //验证码为32位16进制
		$full_url=HOMEURL . $_SERVER['PHP_SELF'];
		$path = str_replace('source/charge.php', '', $full_url) . "api/pay.php";
		//$base	= HOMEURL;
 		//$path   = ($base == 'http://workbench.com/') ? $base.'oss/admintool/api/' : $base.'admintool/api/';
		$site	= HOMEURL;
		$url = $path.'?sid='.$sid.'&paynum='.$paynum.'&mode='.$mode.'&user='.urlencode($user).'&money='.$money.'&gold='.$gold.'&time='.$time.'&ticket='.$ticket;
		$msg = fetchurl($url);
		if(trim($msg) == $g_c["api"]["pay"]["result"]["true"]){
			$log = "{$_LANG['charge']['name']},{$_LANG['charge']['charge']}{$_LANG['detail']['status']}：".$msg."：[{$_LANG['charge']['paynum']}：".$paynum.",{$_LANG['info']['plat']}：".$user."_".$sid.",{$_LANG['charge']['gold']}：".$gold.",{$_LANG['charge']['charge']}{$_LANG['time']['time']}".date('Y-m-d H:i:s', $time).",{$_LANG['info']['service']}：".$sid.",{$_LANG['info']['site']}：,".$site."]";
			cls_entry::load('adminlog')->addlog($log);
			alert("{$_LANG['charge']['charge']}{$_LANG['system']['success']}", "./charge.php");
		}else{
			alert("{$_LANG['charge']['charge']}{$_LANG['system']['fail']}");
		}
		break;
	default:
		$num = 15;
		break;
}

$where = array();
$order = array();
$page = request('page') > 0 ? request('page') : 1 ;
$pagesize = request('num') > 0 ? request('num') : $num ;
$query = "?num=$pagesize";

//充值方式为后台充值
$where[] = array('field'=>'mode','val'=>'admin');

$paynum = request('paynum');
if(!empty($paynum)){
	$where[] = array('field'=>'paynum','val'=>$paynum);
	$query .= "&paynum=$paynum";
}
$pname = request('pname');
if(!empty($pname)){
	$where[] = array('field'=>'user','val'=>$pname);
	$query .= "&pname=$pname";
}
$rname = request('rname');
if(!empty($rname)){
	$where[] = array('field'=>'role_name','val'=>$rname);
	$query .= "&rname=$rname";
}
$goldsort = request('goldsort');
if($goldsort == 1){
	$orderby[] = array('field'=>'gold','type'=>'desc');
	$query .= "&goldsort=1";
	$smarty->assign('goldsort',2);
}elseif($goldsort == 2){
	$orderby[] = array('field'=>'gold','type'=>'asc');
	$query .= "&goldsort=2";
	$smarty->assign('goldsort',1);
}
$moneysort = request('moneysort');
if($moneysort == 1){
	$orderby[] = array('field'=>'money','type'=>'desc');
	$query .= "&moneysort=1";
	$smarty->assign('moneysort',2);
}elseif($moneysort == 2){
	$orderby[] = array('field'=>'money','type'=>'asc');
	$query .= "&moneysort=1";
	$smarty->assign('moneysort',1);
}
$orderby[] = array('field'=>'id','type'=>'desc');

$result = cls_entry::load('charge')->query($where,$orderby,'',$page,$pagesize);
if(!empty($result['result'])){
	$chargelist = $result['result'];
	$page = pagectrl($page, $pagesize, $result['count'],'charge.php'.$query);
}

$smarty->assign('page',$page);
$smarty->assign('chargemode',$chargeMode);
$smarty->assign("chargelist", $chargelist);
$smarty->display("./charge.shtml");
?>