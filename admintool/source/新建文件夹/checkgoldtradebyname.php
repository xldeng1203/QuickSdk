<?php
/**
 * 角色购买统计
 */
require_once('config.inc.php');
$start_time = request('start_time');
$starttime = empty($start_time) ? strtotime(date('Y-m-d 00:00:00')) : strtotime($start_time." 00:00:00");
$end_time = request('end_time');
$endtime = empty($end_time) ? strtotime(date('Y-m-d 23:59:59')) : strtotime($end_time." 23:59:59");
$where = array();
$where[] = array('field'=>'trade_timestamp','val'=>$starttime,'type'=>'>=');
$where[] = array('field'=>'trade_timestamp','val'=>$endtime,'type'=>'<=');
$rolename = request('rolename');
if(!empty($rolename)){
	$where[] = array('field'=>'buyer_name','val'=>$rolename);
}
$orderby = array('field'=>'trade_timestamp','type'=>'desc');
$result = cls_entry::load('log_gold_shop')->query($where,$orderby,'','','','','trade_timestamp,trade_type,trade_result,buyer_id,buyer_name,item_id,price_type,price,buy_num,total_price');
if(!empty($result['result'])){
	$shopinfo = array();
	$shopinfo = $result['result'];
	$totalgold = 0;
	$totalgoldbind = 0;
	foreach ($shopinfo as $key => $val){
		   $goodsinfo = getGoodsInfoById($val['item_id']);
		   $shopinfo[$key]['item_name'] = $goodsinfo['name'];
		   $shopinfo[$key]['buy_type'] = $Global_Log_Resolve['gold_shop']['op_type'][$val['trade_type']];
		   $shopinfo[$key]['buy_result'] = $Global_Log_Resolve['gold_shop']['result_type'][$val['trade_result']];
		   if($val['price_type'] == 2){
		   	   $totalgold += $val['total_price'];
		   }else{
		   	   $totalgoldbind += $val['total_price'];
		   }
		   
	}
}
$smarty->assign("totalgold", $totalgold);
$smarty->assign("totalgoldbind", $totalgoldbind);
$smarty->assign("shopinfo", $shopinfo);

$smarty->display("checkgoldtradebyname.shtml");
?>