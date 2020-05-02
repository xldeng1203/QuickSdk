<?php
/*
 * 平台相关配置
 */
			
$g_c["interface"]["msg"] = array(
					"ticket_error"		=> -1,	// ticket错误
					"param_error"		=> -2,	// 参数错误
					"data_error"		=> -3,	// 数据错误
					"ok"				=> 1,	// OK
					"user_name_error"	=> 2,	// 平台名错误
);

//登录游戏后台密钥（key）
$g_c["login_gamemanager_pwd"] = array(
					"key" => "ZTQz@20111020_by_LSQ!^$^#&",	//总后台登录游戏后台验证密钥
);

$g_c['ctype'] = array(
	0 => array('en_name'=>'RMB', 'cn_name'=>'人民币'),
	1 => array('en_name'=>'HKD', 'cn_name'=>'港币'),
	2 => array('en_name'=>'TWD', 'cn_name'=>'台币'),
    3 => array('en_name'=>'VND', 'cn_name'=>'越南盾'),
    4 => array('en_name'=>'MYR', 'cn_name'=>'马来西亚币'),
);

$g_c['rate'] = array(
		//转换为人民币的汇率
		0 => 1,
		1 => 0.8,
		2 => 0.2,
        3 => 0.03160,
        4 => 2,
);

?>