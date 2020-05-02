<?php
/* 
 * 内部充值接口
 * 充值订单
 * 验证传递的参数是否齐全
 * 验证IP是否合法
 * 验证加密串是否正确
 * 验证超时
 * 验证用户是否存在
 * 验证订单唯一（只插入充值成功的记录到表，充值失败写入日志，用于简化验证流程）
 * 操作充值数据（存在更新，不存在则插入新记录）
 */
require_once ('../source/global.config.inc.php');
require_once (Global_DIR.'configure/api.php');//加载API配置参数
require_once (Global_DIR.'configure/server.php');
require_once (Global_DIR.'include/functions.php'); // 加载公共函数类库
$post = array();
$post['ip'] = get_client_ip();
$post['sid'] 	= request('sid');// 服务器ID
$post['paynum'] = request('paynum');// 订单号
$post['mode'] 	= request('mode');// 充值方式
$post['user'] 	= request('user');// 平台帐号
$post['money'] 	= request('money');// 充值金额
$post['gold'] 	= request('gold');// 充值元宝
$post['time'] 	= request('time');// 时间戳
$post['ticket'] = request('ticket');// 验证串
$post['query']  = $_SERVER['QUERY_STRING'];//参数
$post['referer'] = $_SERVER['HTTP_REFERER'];
$post['host'] = $_SERVER['HTTP_HOST'];
$post['md5_key']= PAYKEY;//对应平台充值加密KEY
//$post['allow_ip']= $g_c['api']['pay']['allow_ip'][$g_c['api']['plat']];//对应平台允许使用的IP
$post['result']= $g_c['api']['pay']['result'];//反回结果状态
$result = cls_entry::load('api')->pay($post);
if($result['status']){
	exit($result['result']);
}else{
	exit($result['error']);
}
?>