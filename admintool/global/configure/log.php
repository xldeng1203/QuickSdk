<?php
/*
 * 日志配置
 */

global $g_c;
$log_dir = $g_c["serverlog_dir"];

//备份已经读取的log日志的路径
//$g_c["log_bak"] = $log_dir."log_bak/";

//玩家登录日志,目前只用于实时在线的统计
$g_c["log"] = array(
					"api_curr_online" => array($log_dir."gamelog_0/rolenum/rolenum*.txt"),
			);

// 游戏里生成log的路径(/usr/local/serverlog/)
// 三个游戏日志都启用。
$log_index = 0;
for($i = 0; $i < $g_c["game_config_gameworld_num"];  $i ++) {
	$gamelog [$log_index] = "gamelog_" . $i;
	$log_index ++;
}

//断线日志
for($i = 1; $i < $g_c["game_config_gateway_num"] + 1; $i ++) {
	$dislog [$log_index] = "gatewaylog_" . $i;
	$log_index ++;
}

foreach($gamelog as $k=>$g){
	$g_c["log_dir"][$k] = array(
						'shop'         => $log_dir.$g."/shop/",		         //商城
						'taskstats'    => $log_dir.$g."/taskstats/",		 //任务
						'money_gold'   => $log_dir.$g."/money_gold/",		 //元宝流动
						'money_coin'   => $log_dir.$g."/money_coin/",		 //银两流动
						'functionstats'=> $log_dir.$g.'/functionstats/',     //功能活跃度数据统计
						'chestshop'    => $log_dir.$g.'/chestshop/',         //祈福开宝箱日志
						'mount'        => $log_dir.$g.'/mount/',             //坐骑相关操作日志
						//'userstate'    => $log_dir.'loginlog/',
						//'rolenum'      => $log_dir.'loginlog/',
						'pet'          => $log_dir.$g.'/pet/',               //宠物相关日志
						'equipment'    => $log_dir.$g.'/equipment/',         //装备操作日志
						'item'         => $log_dir.$g.'/item/',              //物品合成日志
						'role_upgrade' => $log_dir.$g.'/role_upgrade/',      //玩家升级日志
						//'mail'         => $log_dir.'globallog/mail/',      //邮件日志
						//'publicsale'   => $log_dir.'globallog/publicsale/',//拍卖日志
						'knapsack'     => $log_dir.$g.'/knapsack/',          //物品日志
						'drop'         => $log_dir.$g.'/drop/',              //世界BOSS掉落日志
						'battlefield'  => $log_dir.$g.'/battlefield/',       //战场日志
						'monitor'      => $log_dir.$g.'/monitor/',           //玩家行为监控
						'mentality'    => $log_dir.$g.'/mentality/',         //境界提升日志
				);	
}

$g_c["log_dir"][0]['userstate'] = $log_dir.'gamelog_0/userstate/';
$g_c["log_dir"][0]['rolenum']   = $log_dir.'gamelog_0/rolenum/';
$g_c["log_dir"][0]['mail']      = $log_dir.'globallog/mail/';
$g_c["log_dir"][0]['publicsale']= $log_dir.'globallog/publicsale/';
$g_c["log_dir"][0]['p2ptrade']  = $log_dir.'globallog/p2ptrade/';
$g_c["log_dir"][0]['marrylog']  = $log_dir.'globallog/marry/';

foreach($dislog as $k=>$d){
	$g_c["log_dir"][$k] = array(
						'disconnect'    => $log_dir.$d."/"		             //断线日志
				);		
}

/*
 * 游戏生成的log日志类型
 * 如，gold_shop: 元宝商店的log日志之类
 * 用于循环获取日志内容
 */
$g_c["data_type"] = array(
			'shop' => array(
					'type_exp' => "/\[Shop::(\w+)/",
					'op_type' => array(
				 //[2012-10-17 15:50:03] Shop (Info): [Shop::Buy Succ][user[2102161 诸葛寒晓] item_id:20021 num:1 consume_type:2 total_price:1] 
				 //[2011-10-21 11:00:58] GoldShop (Info): [Shop::BuyShopItem Succ][user[%d:%d %s] item_id:%d price_type(解释：绑定或非绑):%d price:%u buy_num:%d total_price:%u]
				 //[2011-10-21 11:00:58] GoldShop (Info): [2012-01-17 16:48:30] GoldShop (Info): [Shop::BuyShopItem Succ][user[0:1 熊夏] item_id:10049 price_type:2 price:148 buy_num:1 total_price:148]
							'Buy' => array(
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Shop::(\w+) (\w+)\]\[user\[(\d+) (.*)\] item_id:(\d+) num:(\d+) consume_type:(\d+) total_price:(\d+)\]/",
								'data' => array(
											'trade_timestamp','trade_type','trade_result','buyer_id','buyer_name','item_id','buy_num','price_type','total_price',
										),
								),
					),
			),
			//任务 
			'taskstats' => array(
					'type_exp' => "/\[TaskResult::(\w+)/",
					'op_type' => array(
			//[2011-10-21 11:00:58] TaskState (Info): [TaskResult::accept][role[0:7] task_id:27 task_level:9 task_type:0 role_professional:0]
							'accept' => array(
								'exp' => "/\[((.{4}-.{2}-.{2} .{2}:.{2}:.{2}))\].*\[role\[(\d+) (.*)] task_id:(\d+) task_level:(\d+) task_type:(\d+) role_professional:(\d+)\]/",
								'data' => array(
											'accept_timestamp','record_timestamp','role_id','role_name','task_id','task_level','task_type','role_professional',
										),
								),
			//[2011-10-21 11:01:30] TaskState (Info): [TaskResult::finish][role[0:7] task_id:27 task_level:9 task_type:0 role_professional:0]
							'finish' => array(
								'exp' => "/\[((.{4}-.{2}-.{2} .{2}:.{2}:.{2}))\].*\[role\[(\d+) (.*)] task_id:(\d+) task_level:(\d+) task_type:(\d+) role_professional:(\d+)\]/",
								'data' => array(
											'finish_timestamp','record_timestamp','role_id','role_name','task_id','task_level','task_type','role_professional',
										),
								),
							'quit' => array(
								'exp' => "/\[((.{4}-.{2}-.{2} .{2}:.{2}:.{2}))\].*\[role\[(\d+) (.*)] task_id:(\d+) task_level:(\d+) task_type:(\d+) role_professional:(\d+)\]/",
								'data' => array(
											'quit_timestamp','record_timestamp','role_id','role_name','task_id','task_level','task_type','role_professional',
										),
								),
							//[2013-03-09 10:08:35] TaskState (Info): [TaskResult::Complete][role[801 楚忆] task_id:2030 task_level:60 task_type:0 role_professional:3]
							'Complete'=>array(
								'exp'=>"/\[((.{4}-.{2}-.{2} .{2}:.{2}:.{2}))\].*\[role\[(\d+) (.*)] task_id:(\d+) task_level:(\d+) task_type:(\d+) role_professional:(\d+)\]/",
								'data' => array(
											'complete_timestamp','record_timestamp','role_id','role_name','task_id','task_level','task_type','role_professional',
										),							
								),
					),	
			),
			'money_gold' => array(
					'type_exp' => "/\[Money::(\w+)/",
					'op_type' => array(
						//[2012-10-12 10:57:19] MoneyGold (Info): [Money::UseGold Succ][user[阮冰冰 2332] type:QuestionItemAutoSelect use_gold:20 remain_gold:888794183 remain_gold_bind:888836258]
						//[2011-10-26 19:34:17] MoneyGold (Info): [Money::UseGold Succ][user[%d:%d %s] type:%s use_gold:%u remain_gold:%u remain_gold_bind:%u]
							'UseGold' => array(
									//'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(\d+):(\d+) (.*)\] type:(\w+) use_gold:(\d+) remain_gold:(\d+) remain_gold_bind:(\d+)\]/",
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) use_gold:(\d+) remain_gold:(\d+)\]/",
									/*'data' => array(
										'op_timestamp','op_type','op_result','role_db_index','role_id','role_name','use_for','use_gold','remain_gold','remain_gold_bind',
									),*/
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','use_gold','remain_gold',
									),
							),
						//[2011-10-26 19:34:17] MoneyGold (Info): [Money::UseGoldBind Succ][user[%d:%d %s] type:%s use_gold_bind:%u remain_gold:%u remain_gold_bind:%u]
							'UseGoldBind' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) use_gold_bind:(\d+) remain_gold_bind:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','use_gold_bind','remain_gold_bind',
									),
							),
						//[2011-10-26 19:34:17] MoneyGold (Info): [Money::Add Succ][user[%d:%d %s] type:%s add_gold:%u add_gold_bind:%u remain_gold:%u remain_gold_bind:%u]
							'AddGold' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) add_gold:(\d+) remain_gold:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_gold','remain_gold',
									),
							),
							//[2011-10-26 19:34:17] MoneyGold (Info): [Money::AddGoldBind Succ][user[0:15 ewq0] type:Other add_gold_bind:20000 remain_gold:520000 remain_gold_bind:520000]
							'AddGoldBind' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) add_gold_bind:(\d+) remain_gold_bind:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_gold_bind','remain_gold_bind',
									),
							),
							'AddAllGold' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) add_gold:(\d+) add_gold_bind:(\d+) remain_gold:(\d+) remain_gold_bind:(\d+)\]/",
									'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_gold','add_gold_bind','remain_gold','remain_gold_bind',
									),
							),
					),
			),
			'money_coin' => array(
					'type_exp' => "/\[Money::(\w+)/",
					'op_type' => array(
							//去掉了数据库索引，先角色名，再角色ID,去掉了非绑定铜币
							//[2012-10-12 10:47:03] MoneyCoin (Info): [Money::UseCoinBind Succ][user[令狐波寒 309] type:SoulInnEmploy use_coin_bind:7000 remain_coin_bind:1651855]
							'UseCoinBind' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) use_coin_bind:(\d+) remain_coin_bind:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','use_coin_bind','remain_coin_bind',
									),
							),
							//[2011-10-27 20:28:29] MoneyCoin (Info): [Money::UseCoin Succ][user[0:21 司马思岚] type:NpcShopUse use_coin:300 remain_coin:9951560 remain_coin_bind:0]
							'UseCoin' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) use_coin:(\d+) remain_coin:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','use_coin','remain_coin',
									),
							),
		   					//[2011-10-31 13:16:27] MoneyCoin (Info): [Money::UseAllCoin Succ][user[0:121 米娜] type:EquipStrengthen use_coin:0 use_coin_bind:288 remain_coin:0 remain_coin_bind:11428]
							'UseAllCoin' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) use_coin:(\d+) use_coin_bind:(\d+) remain_coin:(\d+) remain_coin_bind:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','use_coin','use_coin_bind','remain_coin','remain_coin_bind',
									),
							),
							//[2012-10-12 10:53:30] MoneyCoin (Info): [Money::Add Succ][user[燕幼松 4162] type:DefGift add_coin_bind:1000 remain_coin_bind:1100]
							//[2011-10-21 10:42:53] MoneyCoin (Info): [Money::Add Succ][user[0:7 耿语梅] type:TaskReward add_coin:0 add_coin_bind:600 remain_coin:0 remain_coin_bind:1600]
							'AddCoin' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(.*) add_coin:(\d+) remain_coin:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_coin','remain_coin',
									),
							),
							//[2012-10-12 10:49:13] MoneyCoin (Info): [Money::AddCoinBind Succ][user[令狐波寒 309] type:Pick add_coin_bind:55 remain_coin_bind:1648232]
							//[2011-10-30 13:26:42] MoneyCoin (Info): [Money::AddCoinBind Succ][user[0:611 lisi] type:WorldBossReward add_coin_bind:18888 remain_coin:0 remain_coin_bind:3870625]
							'AddCoinBind' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) add_coin_bind:(\d+) remain_coin_bind:(\d+)\]/",
									'data' => array(
										'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_coin_bind','remain_coin_bind',
									),
							),
							//[2011-10-26 19:07:11] MoneyCoin (Info): [Money::AddCoin Succ][user[0:15 ewq0] type:Other add_coin:20000 remain_coin:20000 remain_coin_bind:1000]
							'AddAllCoin' => array(
							 'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Money::(\w+) (\w+)\]\[user\[(.*) (\d+)\] \[level:(\d+)\] type:(\w+) add_coin:(\d+) add_coin_bind:(\d+) remain_coin:(\d+) remain_coin_bind:(\d+)\]/",
									'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','lv','use_for','add_coin','add_coin_bind','remain_coin','remain_coin_bind',
									),
							),
						),
				),
				//功能活跃度日志
				'functionstats' => array(
					'type_exp' => "/\[(Join)\]/",
					'op_type' => array(
						//[2012-10-28 13:00:06] FunctionStats (Info): [Join][user[陈武不 124] type:DailyBuyCoin times:10]
						'Join' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[user\[(.*) (\d+)\] type:(\w+) times:(\d+)\]/",
							'data' => array(
								'timestamp','role_name','role_id','type','times',
							),
						),
					),
				),
				//祈福日志
				'chestshop' => array(
					'type_exp' => "/\[ChestShop::(\w+)/",
					'op_type' => array(
						//[2012-11-01 09:57:36] ChestShop (Info): [ChestShop::Buy Succ][user[2127956 姬丹念] item_id:24101 num:1 is_bind:0]
						'Buy'=> array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[user\[(\d+) (.*)\] item_id:(\d+) num:(\d+) is_bind:(\d+)\]/",
							'data' => array(
								'timestamp','role_id','role_name','item_id','num','is_bind',
							),
						),
					),
				),
				//玩家登录，上线下线日志
				'userstate' => array(
					'type_exp' => "/\[LoginServer::(\w+)/",
					'op_type'  => array(
						//[2012-11-06 11:55:53] GLOBAL_USER (Info): [LoginServer::OnGlobalUserLogin Success] user[曹儿, 2125060] ip[2102925610].
						//玩家登录
						'OnGlobalUserLogin' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[LoginServer::(\w+) (\w+)\] user\[(.*), (\d+)\] ip\[(\d+)\] is_micro_pc\[(\d+)\].*/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','ip','is_micro_pc'
							),
						),
						//[2012-11-06 11:52:29] GLOBAL_USER (Info): [LoginServer::OnGlobalUserLogout ret:1] user[周修刀, 2128979] ip[2102925611].
						//玩家下线
						'OnGlobalUserLogout' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[LoginServer::(\w+) ret:(\d+)\] user\[(.*), (\d+)\] ip\[(\d+)\] is_micro_pc\[(\d+)\].*/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','ip','is_micro_pc'
							),	
						),
					),
				),
				//坐骑日志
				'mount' => array(
					'type_exp' => "/\[Mount::(\w+)/",
					'op_type' => array(						
						//坐骑进阶
						//[2012-11-16 17:53:11] Mount (Info): [MountFunction::MountLift Succ][user[楚安安 2097534][mount_name:烈魂 mount_id:3 mount_level:3 lift_exp:7700->7750 stuff_name:初级进阶丹 stuff_id:24600 stuff_num:4 cost_coin:1200]
						//[2012-11-16 17:53:11] Mount (Info): [MountFunction::MountLift SuccAndTransform][user[楚安安 2097534][mount_name:烈魂->鎏金战马 mount_id:3->4 mount_level:3->4 lift_exp:7750->0 stuff_name:初级进阶丹 stuff_id:24600 stuff_num:4 cost_coin:1200]	
						'MountUpgrade' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Mount::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[mount_id:(\d+) mount_index:(\d+) mount_name:(.*) grade:(\d+) new_grade:(\d+) bless_val:(\d+) new_bless_val:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+)\]/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','mount_id','mount_index','mount_name','level','new_level','lucky_val','new_lucky_val','cost_coin','cost_coin_bind','cost_stuff_id','cost_stuff_count',
							),							
						),
						//坐骑强化
						//[2012-11-20 13:59:33] Mount (Info): [MountFunction::MountStrengthen Succ][user[2109907 叶妙荷][mount_name:圆圆 mount_id:12 strengthen_level:109->110 cost_stuff_id:0 uplevel_rune_id:0 cost_coin:10000 auto_buy_cost_gold:1024]
						//cost_stuff_id = 0 代表使用元宝自动购买强化宝石
						'MountStrength' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Mount::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[mount_id:(\d+) mount_index:(\d+) mount_name:(.*) strength_level:(\d+) new_strength_level:(\d+) lucky_val:(\d+) new_lucky_val:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+) cost_lucky_item:(\d+)\]/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','mount_id','mount_index','mount_name','level','new_level','lucky_val','new_lucky_val','cost_coin','cost_coin_bind','cost_stuff_id','cost_stuff_count','cost_lucky_item',
							),							
						),
						//添加坐骑
						//[Mount::AddMount Succ][user[%d: %s] mount_id:%d mount_index:%d]
						'AddMount' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Mount::(\w+) (\w+)\]\[user\[(.*) (\d+)\] mount_id:(\d+) mount_index:(\d+) mount_name:(.*)\]/",
							'data'=> array(
								'timestamp','type','status','role_id','role_name','mount_id','mount_index','mount_name',
							),							
						),
						//删除坐骑
						//[Mount::RemoveMount Succ][user[%d: %s] index:%d mount_data:%s]
						'RemoveMount' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Mount::(\w+) (\w+)\]\[user\[(.*) (\d+)\] mount_id:(\d+) mount_index:(\d+) mount_name:(.*)\]/",
							'data'=> array(
								'timestamp','type','status','role_id','role_name','mount_id','mount_index','mount_name',
							),							
						),																													
					),
				),
				//宠物日志
				'pet' => array(
					'type_exp' => "/\[Pet::(\w+)/",
					'op_type'  => array(
							//添加宠物
							'AddPet' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name',
									),
							),
							//删除宠物
							'RemovePet' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name',
									),
							),
							//宠物成长
							'PromoteGrow' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) grow_exp:(\d+) new_grow_exp:(\d+) grow_level:(\d+) new_grow_level:(\d+) cost_gold:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','grow_exp','new_grow_exp','grow_level','new_grow_level','cost_gold',
									),
							),
							//宠物灵性
							'Feed' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) jingli:(\d+) new_jingli:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','jingli','new_jingli','cost_stuff_id','cost_stuff_count',
									),
							),
							//宠物悟性 - 摇骰
							'Roll' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) cost_stuff_id:(\d+) cost_stuff_count:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','cost_stuff_id','cost_stuff_count',
									),
							),
							//宠物悟性 - 开骰
							'OpenRoll' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) wx_exp:(\d+) new_wx_exp:(\d+) wx_level:(\d+) new_wx_level:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','jingli','new_jingli','cost_stuff_id','cost_stuff_count',
									),
							),
							//宠物悟性 - 元宝摇骰
							'GoldRoll' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) cost_gold:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','cost_gold',
									),
							),
							//宠物技能 - 学习
							'LearnSkill' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) skill_id:(\d+) skill_level:(\d+) cost_gold:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','oper_value','new_level','cost_gold','cost_stuff_id','cost_stuff_count',
									),
							),
							//宠物技能 - 遗忘
							'ForgetSkill' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) skill_id:(\d+) skill_level:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','oper_value','new_level',
									),
							),
							//宠物融合
							'Merge' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) vice_pet_id:(\d+) vice_pet_index:(\d+) vice_pet_name:(.*) jingli:(\d+) new_jingli:(\d+) zizhi:(\d+) new_zizhi:(\d+) level:(\d+) new_level:(\d+) grow_exp:(\d+) new_grow_exp:(\d+) grow_level:(\d+) new_grow_level:(\d+) wuxing_exp:(\d+) new_wuxing_exp:(\d+) wuxing_level:(\d+) new_wuxing_level:(\d+) attr_id:(\d+) new_attr_id:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','vice_pet_id','vice_pet_index','vice_pet_name','jingli','new_jingli','zizhi','new_zizhi','level','new_level','grow_exp','new_grow_exp','grow_level','new_grow_level','wuxing_exp','new_wuxing_exp','wuxing_level','new_wuxing_level','attr_id','new_attr_id',
									),
							),
							//宠物灵穴
							'LingxuePromote' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) lingxue:(\d+) level:(\d+) new_level:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','oper_value','level','new_level','cost_coin','cost_coin_bind','cost_stuff_id','cost_stuff_count',
									),
							),
							//宠物探险
							'Explore' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_id:(\d+) pet_index:(\d+) pet_name:(.*) add_exp:(\d+) reward_item_id:(\d+) reward_item_count:(\d+) cost_gold:(\d+) cost_jingli:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','role_id','pet_id','pet_index','pet_name','oper_value','reward_stuff_id','reward_stuff_count','cost_gold','cost_jingli',
									),
							),
							//宠物图鉴 - 激活
							'ImageActivate' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[image_index:(\d+) image_val:(\d+) new_image_val:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','pet_index','image_val','new_image_val',
									),
							),
							//宠物图鉴 - 提升
							'ImagePromote' => array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[image_index:(\d+) add_exp:(\d+) level:(\d+) new_level:(\d+) cost_egg_count:(\d+) cost_card_count:(\d+)\]/",
									'data'=> array(
											'timestamp','type','status','role_name','pet_index','oper_value','level','new_level','cost_stuff_count','cost_lucky_item',
									),
							),
						//[PetFunction::PetTransfer Succ][user[%s %d][main_pet_id:%d sub_pet_id:%d level:%d->%d wuxing:%d->%d lingxing:%d->%d zizhi:%d->%d evolve_flag:%d->%d kuaile:%d->%d]
						//宠物融合
						'PetTransfer' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[main_pet_id:(\d+) sub_pet_id:(\d+) level:(.*) wuxing:(.*) lingxing:(.*) zizhi:(.*) evolve_flag:(.*) kuaile:(.*)\]/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','pet_id','sub_pet_id','level','pet_wuxing','pet_lingxing','pet_zizhi','evolve_flag','kuaile',
							),							
						),
						//[PetFunction::PetEvolve Succ][user[%s %d][pet_name:%s pet_id:%d evolve_flag:%d->%d cost_coin:%d]
						//宠物进化
						'PetEvolve' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Pet::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[pet_name:(.*) evolve_flag:(.*) cost_coin:(\d+)\]/",
							'data'=> array(
								'timestamp','type','status','role_name','role_id','pet_name','evolve_flag','cost_coin',
							),							
						),
																																												
					),
				),				
				//玩家断线日志
				'disconnect' => array(
					'type_exp' => "/\[Gateway::(\w+)/",
					'op_type'  => array(
						//[2012-11-23 12:34:50] Disconnect (Info): [Gateway::OnDisconnect Succ][user[木二小 519] server_name:GameWorld scene_id:16010 reason:[EraseTimeOut]]
						//玩家登录
						'OnDisconnect' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Gateway::OnDisconnect Succ\]\[user\[(.*) (\d+)\] server_name:(.*) scene_id:(\d+) reason:\[(\w+)\]\]/",
							'data'=> array(
								'timestamp','role_name','role_id','server_name','scene_id','reason',
							),
						),
					),
				),				
				'equipment' => array( //武器装备
					'type_exp' => "/\[EquipFunction::(\w+)/",
					'op_type' => array(
				             //[2012-11-26 10:06:46] Equipment (Info): [EquipFunction::EquipStrengthen Succ][user[杨民潇 2097676][equip_name:羽士90级腰带 equip_id:3411 strengthen_level:101->103 cost_stuff_id:0 stuff_is_bind:1 cost_coin:25000 cost_gold:1024 has_uplevel_item:%d uplevel_item_id:%d]
							'EquipStrengthen' => array(//强化
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) strengthen_level:(\d+) new_strengthen_level:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_gold:(\d+) cost_stuff_id:(\d+) cost_stuff_count:(\d+) cost_stuff_bind_count:(\d+) cost_lucky_item:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','strengthen_level','new_strengthen_level','cost_coin','cost_coin_bind','cost_gold','cost_stuff_id','cost_stuff_count','cost_stuff_bind_count','cost_lucky_item',
										),
								),
							//[2012-11-26 10:10:44] Equipment (Info): [EquipFunction::EquipFlush Succ][user[杨民潇 2097676][equip_name:羽士90级衣服 equip_id:1411 flush_attr[type:24,24,22,18,20,0 value:11,21,24,97,21,0] cost_coin:7000 cost_gold:68]
							'EquipFlush' => array(//洗练
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) flush_attr\[type:(.*) value:(.*)\] cost_bindcoin:(\d+) cost_coin:(\d+) cost_gold:(\d+) flush_index:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','flush_attr_type','flush_attr_value','cost_coin_bind','cost_coin','cost_gold','flush_index',
										),
								),
							//[2012-11-26 10:10:49] Equipment (Info): [EquipFunction::EquipFlushReplace Succ][user[杨民潇 2097676][equip_name:羽士90级衣服 equip_id:1411 old_attr[type:20,24,22,18,20,0 value:15,21,7,97,21,0] new_attr[type:24,24,22,18,20,0 value:11,21,24,97,21,0]]
							'EquipFlushReplace' => array(//洗练替换
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) old_attr\[type:(.*) value:(.*)\] new_attr\[type:(.*) value:(.*)\]\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','flush_attr_type','flush_attr_value','new_flush_attr_type','new_flush_attr_value',
										),
								),
							//[2012-11-27 17:39:31] Equipment (Info): [EquipFunction::EquipUpLevel Succ][user[柯依依 2][equip_name:紫电惊雷->御影追魂 equip_id:10161->10205 cost_coin:36000 has_rune:%d rune_item_id:%d]
							'EquipUpLevel' => array(//装备升级
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) new_equip_name:(.*) new_equip_id:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_stuff_id:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','new_equip_name','new_equip_id','cost_coin','cost_coin_bind','cost_stuff_id',
										),
								),
							//[2012-11-27 17:39:37] Equipment (Info): [EquipFunction::EquipUpQuality Succ][user[柯依依 2][equip_name:御影追魂->御影追魂 equip_id:10205->10208 cost_coin:24000]
							'EquipUpQuality' => array(//装备升品质
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) quality:(\d+) new_quality:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','quality','new_quality','cost_coin','cost_coin_bind',
										),
								),
							//[2012-11-27 17:44:04] Equipment (Info): [EquipFunction::EquipInlayGemstone Succ][user[柯依依 2][equip_name:御影追魂 equip_id:10208 hold_index:0 gemstone_name:3级暴击石 gemstone_id:24302 gemstone_is_bind:1 cost_coin:15000]
							'EquipInlayGemstone' => array(//宝石镶嵌
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) hole_index:(\d+) gemstone_name:(.*) gemstone_id:(\d+) gemstone_is_bind:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','hole_index','cost_stuff_name','cost_stuff_id','cost_stuff_bind_count','cost_coin','cost_coin_bind',
										),
								),
							//[2012-11-27 17:44:09] Equipment (Info): [EquipFunction::EquipUnInlayGemstone Succ][user[柯依依 2][equip_name:御影追魂 equip_id:10208 hold_index:0 gemstone_name:3级暴击石 gemstone_id:24302 gemstone_is_bind:1 cost_coin:30000]
							'EquipUnInlayGemstone' => array(//宝石摘除
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) hole_index:(\d+) gemstone_name:(.*) gemstone_id:(\d+) gemstone_is_bind:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+)\]/",
								'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','hole_index','cost_stuff_name','cost_stuff_id','cost_stuff_bind_count','cost_coin','cost_coin_bind',
										),
								),	
							//[2012-11-27 17:44:09] Equipment (Info): [EquipFunction::EquipUnInlayGemstone Succ][user[柯依依 2][equip_name:御影追魂 equip_id:10208 hold_index:0 gemstone_name:3级暴击石 gemstone_id:24302 gemstone_is_bind:1 cost_coin:30000]
							'EquipDecompose' => array(//装备分解
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_num:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+)\]/",
									'data' => array(
											'timestamp','type','status','role_name','role_id','equip_num','cost_coin','cost_coin_bind',
										),
								),
							'EquipExtend' => array(//装备继承
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) new_equip_name:(.*) new_equip_id:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+) cost_stuff_id:(\d+)\]/",
									'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','new_equip_name','new_equip_id','cost_coin','cost_coin_bind','cost_stuff_id',
										),
								),
							'EquipRefine' => array(//装备精炼
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[EquipFunction::(\w+) (\w+)\]\[user\[(.*) (\d+)\]\[equip_name:(.*) equip_id:(\d+) new_equip_name:(.*) new_equip_id:(\d+) cost_coin:(\d+) cost_coin_bind:(\d+)\]/",
									'data' => array(
											'timestamp','type','status','role_name','role_id','equip_name','equip_id','new_equip_name','new_equip_id','cost_coin','cost_coin_bind',
										),
							),
					),	
				),
				//物品合成日志
				'item' => array(
					'type_exp' => "/\[ItemFunction::(\w+)/",
					'op_type'  => array(
						//[2012-11-26 15:17:12] Item (Info): [ItemFunction::ItemCompose Succ][user[射射射 2104685][product_item_name:2级攻击石 id:24101 bind_num:3 nobind_num:0 cost_item_name:1级攻击石 id:24100 bind_num:0 nobind_num:9 cost_coin:1500]
						//物品合成
						'ItemCompose' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[ItemFunction::ItemCompose Succ\]\[user\[(.*) (\d+)\]\[product_item_name:(.*) id:(\d+) isbind:(\d+) cost_itemid0:(\d+) num0:(\d) cost_itemid1:(\d+) num1:(\d+) cost_itemid2:(\d+) num2:(\d+) cost_itemid3:(\d+) num3:(\d+) cost_bindcoin:(\d+) coin:(\d+)\]/",
							'data'=> array(
								'timestamp','role_name','role_id','product_item_name','id','isbind','cost_itemid0','num0','cost_itemid1','num1','cost_itemid2','num2','cost_itemid3','num3','cost_bindcoin','coin',
							),
						),
						//[2012-11-26 15:20:12] Item (Info): [ItemFunction::BluePrintCompose Succ][user[射射射 2104685][blueprint_id:15 product_item_name:落羽铠 id:1161 bind_num:1 nobind_num:0 cost_coin:0]
						//配方合成
						'BluePrintCompose' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[ItemFunction::BluePrintCompose Succ\]\[user\[(.*) (\d+)\]\[blueprint_id:(\d+) product_item_name:(.*) id:(\d+) bind_num:(\d+) nobind_num:(\d+) cost_coin:(\d+)\]/",
							'data'=> array(
								'timestamp','role_name','role_id','blueprint_id','product_item_name','product_id','product_bind_num','product_nobind_num','cost_coin',
							),
						),
						//[2012-11-26 15:20:45] Item (Info): [ItemFunction::GemstoneBatchCompose Succ][user[射射射 2104685] end_level:2]
						//宝石批量合成
						'GemstoneBatchCompose' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[ItemFunction::GemstoneBatchCompose Succ\]\[user\[(.*) (\d+)\] end_level:(\d+)\]/",
							'data'=> array(
								'timestamp','role_name','role_id','end_level',
							),
						),												
					),
				),								
				//玩家升级日志
				'role_upgrade' => array(
					'type_exp' => "/(RoleUpgrade)/",
					//[2012-11-28 20:06:26] RoleUpgrade (Info): role[2114796 梅惜] upgrade [level:34 time:1354104386 scene:[10014 洛阳城郊] pos:[431 431] old_exp:676939 add_exp:140000 type:TaskReward]
					'op_type' => array(
						'RoleUpgrade' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*role\[(\d+) (.*)\] upgrade \[level:(\d+) time:\d+ scene:\[(\d+) (.*)\] pos:\[(.*)\] old_exp:(\d+) add_exp:(\d+) type:(\w+)\]/",
							'data' => array(
								'timestamp','role_id','role_name','level','scene_id','scene_name','pos','old_exp','add_exp','type',
							),
						),
					),
				),
				//邮件日志
				'mail' => array(
					'type_exp' => "/mail->(.*)\[ret/",
					'op_type' => array(
						//[2012-11-29 18:54:07] Mail (Info): mail->add mail[ret:succ, receiver[uid:2117857], mail_param[sender[uid:0, name:System], mail_index:-1, recv_time:1354186447, kind:2, is_read:0, is_lock:0, subject:{vip;4}礼包attachment[coin:0, coin_bind:0, gold:0, gold_bind:0, item1[item_id:28052, num:1, is_bind:1, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item2[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item3[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], virtual_items[shengwang:0, zhangong:0, zhanhun:0, jingzhoudefen:0, 0, 0, 0, 0, 0, 0]]]]
						'add mail' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*mail->(\w+) mail\[ret:(\w+), receiver\[uid:(\d+)\], mail_param\[sender\[uid:(\d+), name:(.*)\].*, subject:(.*)attachment\[coin:\d+, coin_bind:(\d+), gold:(\d+), gold_bind:(\d+), item1\[item_id:(\d+), num:(\d+),(.*)item2\[item_id:(\d+), num:(\d+),(.*)item3\[item_id:(\d+), num:(\d+),(.*)virtual_items(.*)/",
							'data' => array(
								'timestamp','type','status','rec_uid','send_uid','send_name','subject','coin_bind','gold','gold_bind','item_id1','item_num1','item_param1','item_id2','item_num2','item_param2','item_id3','item_num3','item_param3','virtual_items',
							),
						),
						//[2012-11-29 11:02:53] Mail (Info): mail->fetch attachment affirm[ret:succ, receiver[uid:2097654], mail_param[sender[uid:2097153, name:天璇一], mail_index:13, recv_time:1354158166, kind:1, is_read:1, is_lock:0, subject:ddddattachment[coin:0, coin_bind:0, gold:0, gold_bind:0, item1[item_id:24555, num:1, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item2[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item3[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], virtual_items[shengwang:0, zhangong:0, zhanhun:0, jingzhoudefen:0, 0, 0, 0, 0, 0, 0]]]
						'fetch attachment affirm' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*mail->(\w+) attachment affirm\[ret:(\w+), recver\[uid:(\d+)\],.* mail_param\[sender\[uid:(\d+), name:(.*)\].*, subject:(.*)attachment\[coin:\d+, coin_bind:(\d+), gold:(\d+), gold_bind:(\d+), item1\[item_id:(\d+), num:(\d+),(.*)item2\[item_id:(\d+), num:(\d+),(.*)item3\[item_id:(\d+), num:(\d+),(.*)virtual_items(.*)/",
							'data' => array(
								'timestamp','type','status','rec_uid','send_uid','send_name','subject','coin_bind','gold','gold_bind','item_id1','item_num1','item_param1','item_id2','item_num2','item_param2','item_id3','item_num3','item_param3','virtual_items',
							),
						),
						//[2012-11-29 11:03:55] Mail (Info): mail->remove mail[ret:succ, receiver[uid:2097654], mail_param[sender[uid:0, name:System], mail_index:14, recv_time:1354158223, kind:2, is_read:1, is_lock:0, subject:{vip;4}礼包attachment[coin:0, coin_bind:0, gold:0, gold_bind:0, item1[NULL], item2[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item3[item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], virtual_items[shengwang:0, zhangong:0, zhanhun:0, jingzhoudefen:0, 0, 0, 0, 0, 0, 0]]]]					
						'remove mail' => array(
							'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*mail->(\w+) mail\[ret:(\w+), receiver\[uid:(\d+)\], mail_param\[sender\[uid:(\d+), name:(.*)\].*, subject:(.*)attachment\[coin:\d+, coin_bind:(\d+), gold:(\d+), gold_bind:(\d+), item1\[item_id:(\d+), num:(\d+),(.*)item2\[item_id:(\d+), num:(\d+),(.*)item3\[item_id:(\d+), num:(\d+),(.*)virtual_items(.*)/",
							'data' => array(
								'timestamp','type','status','rec_uid','send_uid','send_name','subject','coin_bind','gold','gold_bind','item_id1','item_num1','item_param1','item_id2','item_num2','item_param2','item_id3','item_num3','item_param3','virtual_items',
							),
						),					
					),
				),								
				'publicsale' => array( //物品拍卖
					'type_exp' => "/public_sale -> (\w+) item/",
			        //[2012-12-25 16:43:42] PublicSale (Info): public_sale -> add item[ ret:succ, role[ uid:2097164, name:楚风风 ], sale_item[sale_index:0, sale_time:1356425022, due_time:1356446622,coin_price:0, gold_price:1, sale_type:101, color:4, level:45, prof:4, item_data:item_id:22003, num:99, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]] ]
					'op_type' => array(
							'add' => array(	//物品拍卖（卖）
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*public_sale -> (\w+) item\[ ret:(\w+), role\[ uid:(\d+), name:(.*) \], sale_item\[.*, sale_time:(\d+), due_time:(\d+),coin_price:(\d+), gold_price:(\d+), (.*), item_data:item_id:(\d+), num:(\d+), (.*)\]/",
								'data' => array(
											'timestamp','type','status','role_id','role_name','sale_time','due_time','coin_price','gold_price','sale_param','item_id','item_num','item_param',
										),
								),
			                 //[2012-12-25 16:46:46] PublicSale (Info): public_sale -> buy item[ buyer[ uid:2097160, name:姬绿绿], seller[ uid:2097164, name:楚风风], sale_item[sale_index:3, sale_time:1356425047, due_time:1356511447,coin_price:0, gold_price:1, sale_type:101, color:4, level:45, prof:4, item_data:item_id:22003, num:99, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]] ]
							'buy' => array(	//物品拍卖（买）
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*public_sale -> (\w+) item\[ buyer\[ uid:(\d+), name:(.*)\], seller\[ uid:(\d+), name:(.*)\], sale_item\[.*, sale_time:(\d+), due_time:(\d+),coin_price:(\d+), gold_price:(\d+), (.*), item_data:item_id:(\d+), num:(\d+), (.*)\]/",
								'data' => array(
											'timestamp','type','buyer_id','buyer_name','role_id','role_name','sale_time','due_time','coin_price','gold_price','sale_param','item_id','item_num','item_param',
										),
								),	
							//[2012-12-24 10:30:08] PublicSale (Info): public_sale ->overtime remove item[ role[ uid:613], sale_item[sale_index:0, sale_time:1356160662, due_time:1356247062,coin_price:0, gold_price:89, sale_type:180, color:2, level:0, prof:4, item_data:item_id:22105, num:1, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]] ]
							'overtime_remove'=>array(  //超时下架
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*public_sale -> (\w+) item\[ role\[ uid:(\d+), name:(.*)\], sale_item\[.*, sale_time:(\d+), due_time:(\d+),coin_price:(\d+), gold_price:(\d+), (.*), item_data:item_id:(\d+), num:(\d+), (.*)\]/",
								'data' => array(
											'timestamp','type','role_id','role_name','sale_time','due_time','coin_price','gold_price','sale_param','item_id','item_num','item_param',
										),
								),
							//[2012-12-25 10:28:26] PublicSale (Info): public_sale ->user remove item[ role[ uid:2097166], sale_item[sale_index:0, sale_time:1356402503, due_time:1356424103,coin_price:0, gold_price:1, sale_type:160, color:4, level:0, prof:4, item_data:item_id:26000, num:1, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]] ]
							'user_remove'=>array(  //主动下架
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*public_sale -> (\w+) item\[ role\[ uid:(\d+), name:(.*)\], sale_item\[.*, sale_time:(\d+), due_time:(\d+),coin_price:(\d+), gold_price:(\d+), (.*), item_data:item_id:(\d+), num:(\d+), (.*)\]/",
								'data' => array(
											'timestamp','type','role_id','role_name','sale_time','due_time','coin_price','gold_price','sale_param','item_id','item_num','item_param',
										),
								),																							
						),	
				),
				'knapsack' => array( //物品放置(获得)、使用、丢弃、消耗
					'type_exp' => "/\[Knapsack::(\w+)/",
					'op_type' => array(
				            //[2012-11-29 11:30:21] Knapsack (Info): [Knapsack::CertainPut Succ][user[西方爵立 2097172] item_id:28101 item_name:宝石袋(1) color:3 is_equipment:0 put_num:1 reason:compose drop_monster_id:1121]
							'CertainPut' => array( //物品放置(获得)
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_id:(\d+) item_name:(.*) color:(\d+) is_equipment:(\d+) put_num:(\d+) reason:(.*) drop_monster_id:(\d+)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_name','color','is_equipment','op_item_num','reason','drop_monster_id',
										),
								),
				            //[2011-10-26 20:14:58] Knapsack (Info): [Knapsack::Use Succ][user[董水梦 17] item_id:22111 item_name:凯旋护腕(1) fail_result:0]
							'Use' => array( //物品使用
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_id:(\d+) item_name:(.*) use_num:(\d+) fail_result:(.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_name','op_item_num','reason',
										),
								),
						//[2011-10-26 20:14:28] Knapsack (Info): [Knapsack::Discard Succ][user[董水梦 17] item_id:20111 item_name:凯旋之剑(1) color:%d discard_num:0]
							'Discard' => array(	//物品丢弃
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_id:(\d+) item_name:(.*) color:(\d+) discard_num:(\d+)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_name','color','op_item_num',
										),
								),
							//[Knapsack::DiscardEquipment Succ][user[%s %d] equipment_data:%s]
						//[2012-11-30 10:09:57] Knapsack (Info): [Knapsack::DiscardEquipment Succ][user[我是财主 2118437] equipment_data:item_id:4050, num:1, is_bind:1, has_param:1, invalid_time:0, item_param[curr_valid_hole_num:3,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]]
							'DiscardEquipment' => array(	//装备丢弃
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] equipment_data:item_id:(\d+), (.*)\]\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_param',
										),
								),							
					//[2011-10-27 19:19:12] Knapsack (Info): [Knapsack::ConsumeItem Succ][user[呼延曼儿 202] item_name:飞天神靴 item_id:11550 consume_num:1 reason:Fly To Place]
							'ConsumeItem' => array(	//物品消耗接口1
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_name:(.*) item_id:(\d+) consume_num:(\d+) reason:(.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_name','item_id','op_item_num','reason',
										),
								),
					//[2011-10-27 19:19:12] Knapsack (Info): [Knapsack::ConsumeEquipment Succ][user[%s %d] equipment_data:%s]
							'ConsumeEquipment' => array(	//装备消耗
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] equipment_data:(.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_name',
										),
								),								
					         //[2011-10-26 19:34:17] Knapsack (Info): [Knapsack::ConsumeItemByIndex Succ][user[ewq0 15] item_id:5009 item_name:初级开孔符(0) consume_num:1 remain_num:29 reason:rune-extend_hole]
							//[Knapsack::ConsumeItemByIndex Succ][user[%s %d] item_id:%d item_name:%s(%d) consume_num:%d remain_num:%d reason:%s]
							'ConsumeItemByIndex' => array(	//物品消耗接口2
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_id:(\d+) item_name:(.*) consume_num:(\d+) remain_num:(\d+) reason:(.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_name','op_item_num','surplus_item_num','reason',
										),
								),
							//[2012-12-12 15:00:32] Knapsack (Info): [Knapsack::ConsumeEquipmentByIndex Succ][user[sale_8_42145_1355245588 52348] equipment_data:item_id:5050, num:1, is_bind:0, has_param:1, invalid_time:0, item_param[curr_valid_hole_num:3,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]]
							'ConsumeEquipmentByIndex' => array(	//物品消耗接口2
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] equipment_data:item_id:(\d+), (.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_param',
										),
								),								
							//[Knapsack::ConsumeItemByIndexList Succ][user[%s %d] item_id:%d item_name:%s(%d) consume_num:%d remain_num:%d reason:%s]
							'ConsumeItemByIndexList' => array(	//物品消耗接口2
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] item_id:(\d+) item_name:(.*) consume_num:(\d+) remain_num:(\d+) reason:(.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_name','op_item_num','surplus_item_num','reason',
										),
								),							
							//[2012-11-28 19:57:22] Knapsack (Info): [Knapsack::ConsumeEquipmentByIndexList Succ][user[叶碧桃 2114457] equipment_data:info[item_id:50, num:1, is_bind:1, has_param:1,invalid_time:0, item_param[curr_valid_hole_num:3,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]]]
							'ConsumeEquipmentByIndexList' => array(	//物品消耗接口2
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[Knapsack::(\w+) (\w+)\]\[user\[(.*) (\d+)\] equipment_data:.*item_id:(\d+), (.*)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_name','role_id','item_id','item_param',
										),
								),							
						),	
				),
				//BOSS掉落日志
				'drop' => array(
					'type_exp' => "/(Drop)/",
					//[2012-12-01 14:00:09] Drop (Info): monster[30590] drop [item_id:20101 is_bind:0] at [scene[10016] pos[396, 298] time[1354341609]] to user[1 阮初琳]
					'op_type' => array(
						'Drop' => array (
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*monster\[(\d+)\] drop \[item_id:(\d+) is_bind:(\d+)\] at \[scene\[(\d+)\] pos\[(.*)\] .* to user\[(\d+)\]/",
								'data' => array(
								'timestamp','monster_id','item_id','is_bind','scene_id','pos','role_id',
							),
						),
					),
				),				
				'trade' => array( //物品交易
					'type_exp' => "/\[TradeRegister::(\w+)/",
					'op_type' => array(
//两个玩家的数据间距太大 [2011-10-18 17:20:13] Trade (Info): [TradeRegister::Trade Succ][user[0:707 袁新春] item_id_1:2088 item_name_1:9级普攻石 item_num_1:50 item_id_2:0 item_name_2: item_num_2:0 item_id_3:0 item_name_3: item_num_3:0 item_id_4:0 item_name_4: item_num_4:0 item_id_5:0 item_name_5: item_num_5:0 coin:0 										other_user[0:706 武寻夜] item_id_1:2048 item_name_1:9级法力石 item_num_1:50 item_id_2:0 item_name_2: item_num_2:0 item_id_3:0 item_name_3: item_num_3:0 item_id_4:0 item_name_4: item_num_4:0 item_id_5:0 item_name_5: item_num_5:0 coin:0]
							'Trade' => array(
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[TradeRegister::(\w+) (\w+)\]\[user\[(\d+):(\d+) (.*)] item_id_1:(\d+) item_name_1:(.*) item_num_1:(\d+) item_id_2:(\d+) item_name_2:(.*) item_num_2:(\d+) item_id_3:(\d+) item_name_3:(.*) item_num_3:(\d+) item_id_4:(\d+) item_name_4:(.*) item_num_4:(\d+) item_id_5:(\d+) item_name_5:(.*) item_num_5:(\d+) coin:(\d+) other_user\[(\d+):(\d+) (.*)] item_id_1:(\d+) item_name_1:(.*) item_num_1:(\d+) item_id_2:(\d+) item_name_2:(.*) item_num_2:(\d+) item_id_3:(\d+) item_name_3:(.*) item_num_3:(\d+) item_id_4:(\d+) item_name_4:(.*) item_num_4:(\d+) item_id_5:(\d+) item_name_5:(.*) item_num_5:(\d+) coin:(\d+)\]/",
								'data' => array(
											'op_timestamp','op_type','op_result','role_db_index','role_id','role_name','item_id_1','item_name_1','item_num_1','item_id_2','item_name_2','item_num_2','item_id_3','item_name_3','item_num_3','item_id_4','item_name_4','item_num_4','item_id_5','item_name_5','item_num_5','op_coin','other_role_db_index','other_role_id','other_role_name','other_item_id_1','other_item_name_1','other_item_num_1','other_item_id_2','other_item_name_2','other_item_num_2','other_item_id_3','other_item_name_3','other_item_num_3','other_item_id_4','other_item_name_4','other_item_num_4','other_item_id_5','other_item_name_5','other_item_num_5','other_op_coin',
										),
								),
						),	
				),
				'rolenum' => array( //历史在线数据原型：[2012-12-03 16:58:45] RoleNum (Info): 1[6/5000] 
					'type_exp' => "/\] (\w+)/",
					'op_type' => array(
							'RoleNum' => array(
								'exp' => "/\[(.*?)\].+:.*\[(\d+)\/(\d+)\]/i",
								'data' => array(
											'timestamp','thread_num','role_num',
										),
								),
					),
				),
				//[2012-12-08 15:24:30] BattleField (Info): [BattleField:: User Join][user[501 蔡春安]]
                //[2012-12-08 15:24:34] BattleField (Info): [BattleField:: User Enter][user[501 蔡春安] battle_field_index:0]
                //[2012-12-08 15:25:55] BattleField (Info): [BattleField:: User Reward][user[501 蔡春安] reward_shengwang:21]
				//战场日志
				'battlefield' => array( 
					'type_exp' => "/\[BattleField:: User (\w+)\]/",
					'op_type' => array(
							'Join' => array(
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[BattleField:: User (\w+)\]\[user\[(\d+) (.*)\]\]/",
								'data' => array(
											'timestamp','type','role_id','role_name',
										),
								),
							'Enter' => array(
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[BattleField:: User (\w+)\]\[user\[(\d+) (.*)\] battle_field_index:(\d+)\]/",
								'data' => array(
											'timestamp','type','role_id','role_name','battle_index',
										),
								),
							'Reward' => array(
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[BattleField:: User (\w+)\]\[user\[(\d+) (.*)\] reward_shengwang:(\d+)\]/",
								'data' => array(
											'timestamp','type','role_id','role_name','shengwang',
										),
								),																	
					),
				),
				//[2012-12-20 09:06:02] p2ptrade (Info): p2ptrade->cancel[trade_id:5823827738997306368]role[ uid:60097, role_name:trade_1_15123_1355432540], other_role[ uid:60103, role_name:trade_8_32401_1355235542]
				'p2ptrade' => array(	//交易日志
					'type_exp' => "/p2ptrade->(.*)\[trade_id/",
					'op_type' => array(
							//取消交易
							//[2012-12-25 15:40:34] p2ptrade (Info): p2ptrade->cancel[trade_id:5825784538982252546] role[uid:2097166, role_name:陈一柔], other_role[uid:2097165, role_name:华蓝曼]
							'cancel' => array( 
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*p2ptrade->(\w+)\[trade_id:(\d+)\] role\[uid:(\d+), role_name:(.*)\], other_role\[uid:(\d+), role_name:(.*)\]/",
								'data' => array(
											'timestamp','type','trade_id','roleA_id','roleA_name','roleB_id','roleB_name',
										),
								),
							//[2012-12-25 15:39:24] p2ptrade (Info): p2ptrade->trade start[trade_id:5825784538982252546] role[uid:2097166, role_name:陈一柔], other_role[uid:2097165, role_name:华蓝曼]
							'trade start' => array( //交易开始
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*p2ptrade->(.*)\[trade_id:(\d+)\] role\[uid:(\d+), role_name:(.*)\], other_role\[uid:(\d+), role_name:(.*)\]/",
								'data' => array(
											'timestamp','type','trade_id','roleA_id','roleA_name','roleB_id','roleB_name',
										),
								),
							//[2012-12-25 15:39:34] p2ptrade (Info): p2ptrade->affirm[trade_id:5825784538982252546] role[uid:2097166, role_name:陈一柔], other_role[uid:2097165, role_name:华蓝曼]
							'affirm' => array( //确认
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*p2ptrade->(\w+)\[trade_id:(\d+)\] role\[uid:(\d+), role_name:(.*)\], other_role\[uid:(\d+), role_name:(.*)\]/",
								'data' => array(
											'timestamp','type','trade_id','roleA_id','roleA_name','roleB_id','roleB_name',
										),
								),
							//[2012-12-25 15:39:33] p2ptrade (Info): p2ptrade->lock[trade_id:5825784538982252546] role[uid:2097166, role_name:陈一柔], other_role[uid:2097165, role_name:华蓝曼], gold: 0, trade_item_list[item_num:1, item1: [item_id:24601, num:3, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item2: [item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item3: [item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]], item4: [item_id:0, num:0, is_bind:0, has_param:0, invalid_time:0, item_param[curr_valid_hole_num:0,flush_lock_flag:0,strengthen_level:0,flush_times:0,hole_list:0,0,0,0,0,0,flush_type_list:0,0,0,0,0,0,0,0,0,0,0,0,flush_attr_list:0,0,0,0,0,0,0,0,0,0,0,0]]
							'lock' => array( //双方锁定
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*p2ptrade->(\w+)\[trade_id:(\d+)\] role\[uid:(\d+), role_name:(.*)\], other_role\[uid:(\d+), role_name:(.*)\], coin: (\d+), trade_item_list\[item_num:(\d+), item1: \[item_id:(\d+), num:(\d+), (.*)\]\], item2: \[item_id:(\d+), num:(\d+), (.*)\]\], item3: \[item_id:(\d+), num:(\d+), (.*)\]\], item4: \[item_id:(\d+), num:(\d+), (.*)\]\]/",
								'data' => array(
											'timestamp','type','trade_id','roleA_id','roleA_name','roleB_id','roleB_name','gold','total_num','item_id1','item_num1','item_param1','item_id2','item_num2','item_param2','item_id3','item_num3','item_param3','item_id4','item_num4','item_param4',
										),
								),
							//[2012-12-25 15:39:10] p2ptrade (Info): p2ptrade->trade succ[trade_id:5825784221154672641] role[uid:2097166, role_name:陈一柔], other_role[uid:2097165, role_name:华蓝曼]
							'trade succ' => array( //交易成功
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*p2ptrade->(.*)\[trade_id:(\d+)\] role\[uid:(\d+), role_name:(.*)\], other_role\[uid:(\d+), role_name:(.*)\]/",
								'data' => array(
											'timestamp','type','trade_id','roleA_id','roleA_name','roleB_id','roleB_name',
										),
								),													
					),	
				),
				//[2013-01-07 12:15:07] Monitor (Info): [SystemMonitor] gold_get:0 gold_consum:0 nobind_item_num:0 p2ptrade_num:0 publicsale_num:0 sendmail_num:0 fetch_attachment_num:0 guild_store_oper_num:0 shop_buy_num:0 chest_shop_buy_num:0
				//[2012-12-29 07:35:12] Monitor (Info): [UserMonitor] [user_id:31907 user_name:team13oss_0_24014_1355013100] [level:80] [gold:0 bind_gold:100 coin_bind:100] gold_get:0 gold_consum:0 nobind_item_num:0 p2ptrade_num:0 publicsale_num:0 sendmail_num:0 fetch_attachment_num:0 guild_store_oper_num:0 shop_buy_num:0 chest_shop_buy_num:0
				'monitor' => array(	//玩家行为监控
					'type_exp' => "/Monitor \(Info\): \[(\w+)\]/",
					'op_type' => array(
							'UserMonitor' => array( //挑战
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[user_id:(\d+) user_name:(.*)\] \[level:(\d+)\] \[gold:(\d+) bind_gold:(\d+) coin_bind:(\d+) coin:(\d+)\] gold_get:(\d+) gold_consum:(\d+) nobind_item_num:(\d+) p2ptrade_num:(\d+) publicsale_num:(\d+) sendmail_num:(\d+) fetch_attachment_num:(\d+) guild_store_oper_num:(\d+) shop_buy_num:(\d+) chest_shop_buy_num:(\d+) coin_get:(\d+) coin_consum:(\d+) mysteryshop_flush_num:(\d+) compose_num:(\d+)/",
								'data' => array(
										'timestamp','role_id','role_name','level','gold','bind_gold','coin_bind','coin','gold_get','gold_consum','nobind_item_num','p2ptrade_num','publicsale_num','sendmail_num','fetch_attachment_num','guild_store_oper_num','shop_buy_num','chest_shop_buy_num','coin_get','coin_consum','mysteryshop_flush_num','compose_num',
										),
								),
							'SystemMonitor'=>array(
									'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].* gold_get:(\d+) gold_consum:(\d+) nobind_item_num:(\d+) p2ptrade_num:(\d+) publicsale_num:(\d+) sendmail_num:(\d+) fetch_attachment_num:(\d+) guild_store_oper_num:(\d+) shop_buy_num:(\d+) chest_shop_buy_num:(\d+) coin_get:(\d+) coin_consum:(\d+) mysteryshop_flush_num:(\d+) compose_num:(\d+)/",
								'data' => array(
										'timestamp','gold_get','gold_consum','nobind_item_num','p2ptrade_num','publicsale_num','sendmail_num','fetch_attachment_num','guild_store_oper_num','shop_buy_num','chest_shop_buy_num','coin_get','coin_consum','mysteryshop_flush_num','compose_num',
										),
								),								
					),	
				),
				//[2013-03-13 14:29:37] Mentality (Info): [Mentality] MentalityUpgradeGengu: [user_id:2145362 user_name:P1F gentu_type:1, gengu_level:25, gengu_max_level:25, consume_gold:18]
				'mentality' => array(
					'type_exp' => "/Mentality \(Info\): \[(\w+)\]/",
					'op_type' => array(
							'Mentality' => array( //挑战
								'exp' => "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*\[user_id:(\d+) user_name:(.*) cur_mentality_type:(\d+)\, cur_mentality_level:(\d+)\]/",
								'data' => array(
											'timestamp','role_id','role_name','gengu_type','gengu_level',
										),
								),								
					),
				),
				//结婚日志
				//[2013-03-29 01:31:09] marrylog (Info): Marry -> marry_reserve [ marry_level[3], user[2101797 天涯狂客], other_user[2113743 雪域菡紫] ]
                //[2013-03-29 01:31:38] marrylog (Info): Marry -> marry_start [ marry_level [3], user[2101797 天涯狂客], other_user[2113743 雪域菡紫] ]
                'marrylog' => array(
					'type_exp' => "/Marry -> (\w+)/",
					'op_type' => array(
						'marry_reserve' =>array(
							'exp'=> "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*Marry -> (\w+) \[ marry_level\[(\d+)\]\, user\[(\d+) (.*)\]\, other_user\[(\d+) (.*)\] \]/",
							'data'=>array(
										'op_timestamp','op_type','marry_level','user_id','user_name','other_id','other_name',
									),
							),
						'marry_start' =>array(
							'exp'=> "/\[(.{4}-.{2}-.{2} .{2}:.{2}:.{2})\].*Marry -> (\w+) \[ marry_level\[(\d+)\]\, user\[(\d+) (.*)\]\, other_user\[(\d+) (.*)\] \]/",
							'data'=>array(
										'op_timestamp','op_type','marry_level','user_id','user_name','other_id','other_name',
									),
							),								
					),
				),



	);
?>
