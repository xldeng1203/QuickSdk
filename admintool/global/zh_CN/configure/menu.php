<?php

// 菜单配置
$allMenu["menu"] = array(
		array(
			"kid" => 1 ,
			"name" => '在线与流失' ,
			"pid" => 0 ,
			"filename" => '' ,
			'icon' => 'icon-plane',
			"show" => 'YES'
		),
		array(
			'kid' => 101,
			'name' => '查看在线角色',
			'pid' => 1,
			'filename' => 'player_online.php',			
			'show' => 'YES'
		),
		array(
			"kid" => 102 ,
			"name" => '在线统计' ,
			"pid" => 1 ,
			"filename" => 'online.php' ,
			"show" => 'YES'
		),
		array(
			'kid' => 103,
			'name' => '地图在线分布',
			'pid' => 1,
			'filename' => 'level_distribution.php',
			'show' => 'YES'
		),
		array(
			'kid' => 105,
			'name' => '等级流失率',
			'pid' => 1,
			'filename' => 'level_lostrate.php',
			'show' => 'YES'
		),
		array(
			'kid' => 106,
			'name' => '玩家流失率',
			'pid' => 1,
			'filename' => 'player_lost.php',
			'show' => 'YES'
		),
		array(
			'kid' => 107,
			'name' => '任务流失率',
			'pid' => 1,
			'filename' => 'taskanalyze.php',
			'show' => 'YES'
		),
		array(
			'kid' => 108,
			'name' => '注册登录统计',
			'pid' => 1,
			'filename' => 'count_reg.php',
			'show' => 'YES'
		),
		array(
			'kid' => 109,
			'name' => '登录人数统计',
			'pid' => 1,
			'filename' => 'player_login.php',
			'show' => 'YES'
		),
		array(
			'kid' => 110,
			'name' => '玩家留存率',
			'pid' => 1,
			'filename' => 'count_live.php',
			'show' => 'YES'
		),
		array(
			'kid' => 111,
			'name' => '在线时长统计',
			'pid' => 1,
			'filename' => 'count_online_time.php',
			'show' => 'YES'
		),				
		array(
			"kid" => 2 ,
			"name" => '充值与消费' ,
			"pid" => 0 ,
			"filename" => '' ,
			'icon' => 'icon-shopping-cart',
			"show" => 'YES'
		),
		array( 
			"kid" => 201 ,
			"name" => '充值报表' ,
			"pid" => 2 ,
			"filename" => 'charge_chart.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 202,
			"name" => 'RMB玩家管理' ,
			"pid" => 2 ,
			"filename" => 'charge_vip.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 203,
			"name" => '首充统计' ,
			"pid" => 2 ,
			"filename" => 'count_first_pay.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 204,
			"name" => '首充等级统计' ,
			"pid" => 2 ,
			"filename" => 'count_lv_first_pay.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 205,
			"name" => '充值等级统计' ,
			"pid" => 2 ,
			"filename" => 'count_lv_pay.php' ,
			"show" => 'YES'
		),
		/**
		array(
			"kid" => 206,
			"name" => '充值区间统计' ,
			"pid" => 2 ,
			"filename" => 'count_pay_section.php' ,
			"show" => 'YES'
		),
		*/
		array(
			"kid" => 207 ,
			"name" => '元宝铜钱消费统计' ,
			"pid" => 2 ,
			"filename" => 'consume_gold.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 208 ,
			"name" => '元宝铜钱库存统计' ,
			"pid" => 2 ,
			"filename" => 'goldstore.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 209 ,
			"name" => '首次消费统计' ,
			"pid" => 2 ,
			"filename" => 'consume_first_gold.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 210 ,
			"name" => '首次消费等级统计' ,
			"pid" => 2 ,
			"filename" => 'consume_lv_first_gold.php' ,
			"show" => 'YES'
		),			
		array(
			"kid" => 3 ,
			"name" => '运营数据' ,
			"pid" => 0 ,
			"filename" => '' ,
			'icon' => 'icon-align-justify',
			"show" => 'YES'
		),
		array(
			"kid" => 301 ,
			"name" => '排行榜' ,
			"pid" => 3 ,
			"filename" => 'personrank.php' ,
			"show" => 'YES'
		),		
		array(
			"kid" => 303 ,
			"name" => '功能活跃度' ,
			"pid" => 3 ,
			"filename" => 'functionstats.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 304 ,
			"name" => '元宝铜钱获得与使用' ,
			"pid" => 3 ,
			"filename" => 'gold_use.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 306 ,
			"name" => '剩余元宝和铜钱排行' ,
			"pid" => 3 ,
			"filename" => 'gold_rank.php' ,
			"show" => 'YES'
		),				
		array(
			"kid" => 309 ,
			"name" => '商城消费统计' ,
			"pid" => 3 ,
			"filename" => 'goldshop.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 4 ,
			"name" => '玩家管理' ,
			"pid" => 0 ,
			"filename" => '' ,
			'icon' => 'icon-user',
			"show" => 'YES'
		),
		array(
			"kid" => 401 ,
			"name" => '玩家列表' ,
			"pid" => 4 ,
			"filename" => 'player_info.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 403 ,
			"name" => '玩家物品列表' ,
			"pid" => 4 ,
			"filename" => 'goods.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 404 ,
			"name" => '仙盟列表' ,
			"pid" => 4 ,
			"filename" => 'guild.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 5 ,
			"name" => '客服管理' ,
			"pid" => 0 ,
			"filename" => '' ,
			'icon' => 'icon-star',
			"show" => 'YES'
		),
		array( 
			"kid" => 502 ,
			"name" => '系统公告' ,
			"pid" => 5 ,
			"filename" => 'notice.php' ,
			"show" => 'YES'
		),
		array( //直连
			"kid" => 504 ,
			"name" => '封号禁言管理' ,
			"pid" => 5 ,
			"filename" => 'command_forbid.php' ,
			"show" => 'YES'
		),	
		array( //直连
			"kid" => 505 ,
			"name" => '同IP监控' ,
			"pid" => 5 ,
			"filename" => 'ip_monitor.php' ,
			"show" => 'YES'
		),		
		array(
			"kid" => 508 ,
			"name" => '设置玩家身份' ,
			"pid" => 5 ,
			"filename" => 'command_guide.php' ,
			"show" => 'YES'
		),
		array( //直连
			"kid" => 509 ,
			"name" => '后台充值与查询' ,
			"pid" => 5 ,
			"filename" => 'charge.php' ,
			"show" => 'YES'
		),
		array( //直连
			"kid" => 512 ,
			"name" => '后台补单' ,
			"pid" => 5 ,
			"filename" => 'add_order.php' ,
			"show" => 'YES'
            ),				
        array(
                "kid" => 7 ,
                "name" => '配置管理' ,
                "pid" => 0 ,
                "filename" => '' ,
                'icon' => 'icon-edit',
                "show" => 'YES'
             ),
        array(
                "kid" => 702 ,
                "name" => '游戏配置' ,
                "pid" => 7 ,
                "filename" => 'game_config.php' ,
                "show" => 'YES'
             ),      
);
?>
