<?php

// 菜單配置
$allMenu["menu"] = array(
		array(
			"kid" => 1 ,
			"name" => '在線與流失' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array(
			'kid' => 101,
			'name' => '查看在線角色',
			'pid' => 1,
			'filename' => 'player_online.php',
			'show' => 'YES'
		),
		array(
			"kid" => 102 ,
			"name" => '在線統計' ,
			"pid" => 1 ,
			"filename" => 'online.php' ,
			"show" => 'YES'
		),
		array(
			'kid' => 103,
			'name' => '各地圖在線分布',
			'pid' => 1,
			'filename' => 'level_distribution.php',
			'show' => 'YES'
		),
		array(
			'kid' => 104,
			'name' => '創號流失率',
			'pid' => 1,
			'filename' => 'reg_lostrate.php',
			'show' => 'YES'
		),
		array(
			'kid' => 105,
			'name' => '等級流失率',
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
			'name' => '任務流失率',
			'pid' => 1,
			'filename' => 'taskanalyze.php',
			'show' => 'YES'
		),
		array(
			'kid' => 108,
			'name' => '註冊登入統計',
			'pid' => 1,
			'filename' => 'count_reg.php',
			'show' => 'YES'
		),
		array(
			'kid' => 109,
			'name' => '登入人數統計',
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
			"kid" => 2 ,
			"name" => '儲值與消費' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array( 
			"kid" => 201 ,
			"name" => '儲值報表' ,
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
			"name" => '首充統計' ,
			"pid" => 2 ,
			"filename" => 'count_first_pay.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 204,
			"name" => '首充等級統計' ,
			"pid" => 2 ,
			"filename" => 'count_lv_first_pay.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 205,
			"name" => '儲值等級統計' ,
			"pid" => 2 ,
			"filename" => 'count_lv_pay.php' ,
			"show" => 'YES'
		),
		/**
		array(
			"kid" => 206,
			"name" => '儲值區間統計' ,
			"pid" => 2 ,
			"filename" => 'count_pay_section.php' ,
			"show" => 'YES'
		),
		*/
		array(
			"kid" => 207 ,
			"name" => '元寶銅錢消費統計' ,
			"pid" => 2 ,
			"filename" => 'consume_gold.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 208 ,
			"name" => '元寶銅錢庫存統計' ,
			"pid" => 2 ,
			"filename" => 'goldstore.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 209 ,
			"name" => '首次消費統計' ,
			"pid" => 2 ,
			"filename" => 'consume_first_gold.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 210 ,
			"name" => '首次消費等級統計' ,
			"pid" => 2 ,
			"filename" => 'consume_lv_first_gold.php' ,
			"show" => 'YES'
		),			
		array(
			"kid" => 3 ,
			"name" => '運營數據' ,
			"pid" => 0 ,
			"filename" => '' ,
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
			"kid" => 302 ,
			"name" => '新手卡' ,
			"pid" => 3 ,
			"filename" => 'newercard.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 303 ,
			"name" => '功能活躍度' ,
			"pid" => 3 ,
			"filename" => 'functionstats.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 304 ,
			"name" => '元寶銅錢獲得與使用' ,
			"pid" => 3 ,
			"filename" => 'gold_use.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 306 ,
			"name" => '剩餘元寶和銅錢排行' ,
			"pid" => 3 ,
			"filename" => 'gold_rank.php' ,
			"show" => 'YES'
		),				
		array(
			"kid" => 309 ,
			"name" => '商城消費統計' ,
			"pid" => 3 ,
			"filename" => 'goldshop.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 4 ,
			"name" => '玩家管理' ,
			"pid" => 0 ,
			"filename" => '' ,
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
			"kid" => 402 ,
			"name" => '登入玩家賬號' ,
			"pid" => 4 ,
			"filename" => 'user_login.php' ,
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
			"name" => '軍團列表' ,
			"pid" => 4 ,
			"filename" => 'guild.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 405 ,
			"name" => '寵物列表' ,
			"pid" => 4 ,
			"filename" => 'pets.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 406 ,
			"name" => '座騎列表' ,
			"pid" => 4 ,
			"filename" => 'mount.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 407 ,
			"name" => '玩家成就' ,
			"pid" => 4 ,
			"filename" => 'achieve.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 408 ,
			"name" => '將魂列表' ,
			"pid" => 4 ,
			"filename" => 'soul.php' ,
			"show" => 'YES'
		),															
		array(
			"kid" => 5 ,
			"name" => '客服管理' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array(
			"kid" => 501 ,
			"name" => '玩家反饋' ,
			"pid" => 5 ,
			"filename" => 'feedback.php' ,
			"show" => 'YES'
		),
		array( 
			"kid" => 502 ,
			"name" => '系統公告' ,
			"pid" => 5 ,
			"filename" => 'notice.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 503 ,
			"name" => '管理員發送郵件' ,
			"pid" => 5 ,
			"filename" => 'sendmail.php' ,
			"show" => 'YES'
		),	
		array( //直連
			"kid" => 504 ,
			"name" => '封號禁言管理' ,
			"pid" => 5 ,
			"filename" => 'command_forbid.php' ,
			"show" => 'YES'
		),	
		array( //直連
			"kid" => 505 ,
			"name" => '同IP監控' ,
			"pid" => 5 ,
			"filename" => 'ip_monitor.php' ,
			"show" => 'YES'
		),		
		array( //直連
			"kid" => 506 ,
			"name" => '物品信息查詢' ,
			"pid" => 5 ,
			"filename" => 'base_goods.php' ,
			"show" => 'YES'
		),
		array( //直連
			"kid" => 507 ,
			"name" => '元寶銅錢扣除' ,
			"pid" => 5 ,
			"filename" => 'gold_deduction.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 508 ,
			"name" => '設置玩家身份' ,
			"pid" => 5 ,
			"filename" => 'command_guide.php' ,
			"show" => 'YES'
		),
		array( //直連
			"kid" => 509 ,
			"name" => '後台儲值與查詢' ,
			"pid" => 5 ,
			"filename" => 'charge.php' ,
			"show" => 'YES'
		),
		array( //直連
			"kid" => 510 ,
			"name" => '聊天監控' ,
			"pid" => 5 ,
			"filename" => 'msg_monitor.php' ,
			"show" => 'YES'
		),
		array( //直連
			"kid" => 511 ,
			"name" => '世界聊天監控' ,
			"pid" => 5 ,
			"filename" => 'cssgchat.php' ,
			"show" => 'YES'
		),		
		array(
			"kid" => 6 ,
			"name" => '日誌管理' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array(
			"kid" => 601 ,
			"name" => '宝箱日誌' ,
			"pid" => 6 ,
			"filename" => 'log_chestshop.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 602 ,
			"name" => '登入日誌' ,
			"pid" => 6 ,
			"filename" => 'log_login.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 603 ,
			"name" => '斷線日誌' ,
			"pid" => 6 ,
			"filename" => 'log_disconnect.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 604 ,
			"name" => '座騎日誌' ,
			"pid" => 6 ,
			"filename" => 'log_mount_feed.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 605 ,
			"name" => '寵物日誌' ,
			"pid" => 6 ,
			"filename" => 'log_pet_wuxing.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 606 ,
			"name" => '裝備日誌' ,
			"pid" => 6 ,
			"filename" => 'log_equipment_strengthen.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 607 ,
			"name" => '物品合成日誌' ,
			"pid" => 6 ,
			"filename" => 'log_item.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 608 ,
			"name" => '郵件日誌' ,
			"pid" => 6 ,
			"filename" => 'log_mail.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 609 ,
			"name" => '世界BOSS掉落日誌' ,
			"pid" => 6 ,
			"filename" => 'log_drop.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 610 ,
			"name" => '背包日誌' ,
			"pid" => 6 ,
			"filename" => 'log_knapsack.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 611 ,
			"name" => '戰場日誌' ,
			"pid" => 6 ,
			"filename" => 'log_battlefield.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 612 ,
			"name" => '拍賣日誌' ,
			"pid" => 6 ,
			"filename" => 'log_publicsale.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 613 ,
			"name" => '交易日誌' ,
			"pid" => 6 ,
			"filename" => 'log_p2ptrade.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 614 ,
			"name" => '商城日誌' ,
			"pid" => 6 ,
			"filename" => 'log_gold_shop.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 615 ,
			"name" => '玩家行為日誌' ,
			"pid" => 6 ,
			"filename" => 'log_monitor.php' ,
			"show" => 'YES'
		),		
		array(
			"kid" => 7 ,
			"name" => '配置管理' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array(
			"kid" => 701 ,
			"name" => '活動配置' ,
			"pid" => 7 ,
			"filename" => 'activity_config.php' ,
			"show" => 'YES'
		),	
		array(
			"kid" => 702 ,
			"name" => '遊戲配置' ,
			"pid" => 7 ,
			"filename" => 'game_config.php' ,
			"show" => 'YES'
		),
		array(
			"kid" => 8 ,
			"name" => '日常運營工作' ,
			"pid" => 0 ,
			"filename" => '' ,
			"show" => 'YES'
		),
		array(
			"kid" => 801 ,
			"name" => '測試進度' ,
			"pid" => 8 ,
			"filename" => 'test_step.php' ,
			"show" => 'YES'
		),							
			
);
?>
