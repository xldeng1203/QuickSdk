<?php
/* 
 * 迅雷充值接口
 */
require_once ('../../source/global.config.inc.php');     //全局配置文件
require_once (Global_DIR.'configure/api.php');           //加载API配置参数
require_once (Global_DIR.'configure/log.php');           //加载log配置路径
require_once (Global_DIR.'include/functions.php');       // 加载公共函数类库
//$post = array();

// $post['ip']     = get_client_ip();
// $post['sid'] 	= request('server');// 服务器ID
// $post['paynum'] = request('orderid');// 订单号
// $post['mode'] 	= 'plat';// 充值方式
// $post['user'] 	= urldecode(request('user'));// 平台帐号
// $post['money'] 	= request('money');// 充值金额
// $post['gold'] 	= request('gold');// 充值元宝
// $post['time'] 	= request('time');// 时间戳
// $post['ticket'] = request('sign');// 验证串
// $post['ext']    = request('ext');   //备用字段
// $post['md5_key']= PAYKEY;//对应平台充值加密KEY
// $post['limit_ip'] = $g_c['api']['pay_allow_ip'][PLATNAME];
// $post['error_param'] = '-1'; //参数不全
// $post['error_check'] = '-2'; //验证失败
// $post['error_user'] = '-3'; //用户不存在
// $post['error_time'] = '-4'; //请求超时
// $post['error_sid']  = '-5'; //服务器编号错误
// $post['error_ip']  = '-6'; //IP错误
// $post['error_repeat'] = '2'; //订单重复
// $post['success'] = '1';  //充值成功
// $post['check_sign'] = md5($post['paynum'].$post['user'].$post['gold'].$post['money'].$post['time'].$post['md5_key']);
// $result = cls_entry::load('api_app')->pay($post);
// if($result['status']){
// 	exit($result['result']);
// }else{
// 	exit($result['error']);
// }


echo "Hello world!12345<br>";

	$post['sign'] 			= request('sign');				// 充值金额
	$post['md5Sign'] 		= request('md5Sign');			// 充值元宝
	$post['callback_key'] 	= request('callback_key');		// 时间戳
	$post['nt_data'] 		= request('nt_data');			// 验证串

	echo "sign=".$post["sign"];
	echo "<br>";
	echo "md5Sign=".$post["md5Sign"];
	echo "<br>";
	echo "callback_key=".$post["callback_key"];
	echo "<br>";
	echo "nt_data=".$post["nt_data"];
	echo "<br>";

	$result = cls_entry::load('quicksdkAsy')->OnQuickPay($post);
	if($result['status']){
		exit($result['result']);
	}else{
		exit($result['error']);
	}
?>