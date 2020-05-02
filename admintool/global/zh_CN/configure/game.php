<?php
/*
 * 游戏参数的一般配置
 */
$g_global["game_level"] = 100; //游戏等级

$g_global["language"] = array(
                      'zh_CN'=>'简体中文',
                      'zh_TW'=>'繁体中文',
);

//后台系统功能 - 全局命令：GlobalServer分类
$g_global["GlobalServer"] = array(
									'Cmd Reload 1 0'=>'重读任务配置',
									'Cmd Reload 2 0'=>'重读武将配置',
									'Cmd Reload 3 0'=>'重读物品配置',
									'Cmd Reload 4 0'=>'重读怪物配置',
									'Cmd Reload 5 0'=>'重读BOSS配置',
									'Cmd ReloadG 1'=>'重读云游BOSS配置',
);


//所有排行类型：0 等级，1财富，2成就，3修为
$g_global["all_rank_type"] = array(
	0 => array('title'=>'综合战力榜','value'=>'战斗力'),
	1 => array('title'=>'等级榜','value'=>'等级'),																	
	2 => array('title'=>'仙女战力榜','value'=>'仙女战力'),
	3 => array('title'=>'装备战力榜','value'=>'装备战力'),
	4 => array('title'=>'魅力总榜','value'=>'魅力值'),
	5 => array('title'=>'阵营1综合战力榜','value'=>'战斗力'),
	6 => array('title'=>'阵营2综合战力榜','value'=>'战斗力'),
	7 => array('title'=>'阵营3综合战力榜','value'=>'战斗力'),
	8 => array('title'=>'坐骑战力榜','value'=>'坐骑战力'),
	9 => array('title'=>'仙剑战力榜','value'=>'仙剑战力'),
	10 => array('title'=>'修炼战力榜','value'=>'修炼战力'),
	11 => array('title'=>'羽翼战力榜','value'=>'羽翼战力'),
	12 => array('title'=>'魅力榜','value'=>'魅力值'),
	13 => array('title'=>'男性魅力榜','value'=>'魅力值'),
	14 => array('title'=>'女性魅力榜','value'=>'魅力值'),
	15 => array('title'=>'攻城战排行榜','value'=>'战功'),
	16 => array('title'=>'群仙乱斗排行榜','value'=>'战功'),
	);


//装备前缀绑定属性
$equi_prefix_bind_attr = array(
							0=>"默认",
							1=>"无前缀绑定",
							10=>"粗糙非绑",
							11=>"粗糙绑定",
							20=>"普通非绑",
							21=>"普通绑定",
							30=>"精良非绑",
							31=>"精良绑定",
							40=>"完美非绑",
							41=>"完美绑定",
);

//中文星期
$week = array(
	'星期日',
	'星期一',
	'星期二',
	'星期三',
    '星期四',
	'星期五',
	'星期六',
);

//消费点列表
$CONSUME = array(
	//消费
	"luckyroll_consume"                   =>	"幸运转盘",
	"SendMailConsume"                     =>	"邮寄元宝",
	"BuyVipLevelRewardGift"               =>	"购买vip等级礼包",
	"MarryCost"                           =>	"结婚花费",
	"DiscountBuyItem"                     =>	"一折抢购",
	"PublicSaleBuy"                       =>	"购买寄售物品",
	"TradeOut"                            =>	"p2p交易",
	"SystemCmdUse"                        =>	"系统命令花费",
	"OneKeyFinishTumo"                    =>	"一键日常任务",
	"OneTaskCommitTumo"                   =>	"双倍日常任务",
	"ClearMentalityCD"                    =>	"清理单次经脉CD",
	"MentalityClearCD"                    =>	"永久清楚经脉CD",
	"MentalityUpgradeGengu"               =>	"提升根骨",
	"NationalBossBuyGongji"               =>	"全民Boss购买Buff",
	"BatchAnswerQuestion"                 =>	"一键答题",
	"BuyChanllengeJoinTimes"              =>	"挑战本购买参与次数",
	"BuyChallengeFieldJoinTimes"          =>    "竞技场购买参与次数",
	"QiFuBuyCoin"                         =>	"祈福铜币花费",
	"QiFuBuyYuanLi"                       =>	"祈福真气花费",
	"QiFuBuyXianHun"                      =>	"祈福仙魂花费",
	"FlushHuSongTaskColor"                =>	"刷新护送任务花费",
	"BuyHusongTimes"                      =>	"购买护送次数",
	"ReAlive"                             =>	"复活",
	"ShopBuy"                             =>	"商城购买",
	"OneKeyCompleteTask"                  =>	"一键仙盟任务",
	"CreateGuild"                         =>	"创建仙盟",
	"GuildAddGuildExp"                    =>	"仙盟捐献",
	"BuySupplyTiliCost"                   =>	"购买体力",
	"BuySupplyHpCost"                     =>	"购买血包",
	"ChestShopBuy"                        =>	"寻宝",
	"GiveFlower"                          =>	"送花",
	"XianjianClearCD"                     =>	"清除仙剑CD",
	"XianMengZhanGuildCall"               =>	"仙盟战仙盟召唤",
	"XiannvYuanshenUplevel"               =>	"仙女缠绵",
	"SignInFindBack"                      =>	"福利签到找回",
	"WelfareFindActivity"                 =>	"福利活动找回",
	"GetOfflineExp"                       =>	"福利离线经验",
	"DailyFind"                           =>	"日常活动找回",
	"SystemCmdUse"                        =>	"系统命令消耗",
	"MazeBuyMove"                         =>	"迷宫购买行动力",
	"KnapsackStorageExtendGridNum"        =>	"扩充背包仓库",
	"WingJinhua"                          =>	"提升羽翼",
	"OpenGift"                            =>	"开礼包",
	"GuildPartyDoubleReward"              =>	"仙盟酒会双倍奖励",
	"GuildPartyClearCD"                   =>	"仙盟酒会清理CD",
	"GuildPartyGatherCount"               =>	"仙盟酒会重置采集次数",
	"WabaoQuickComplete"                  =>	"仙女掠夺快速完成",
	"WabaoBuyJoinTimes"                   =>	"仙女掠夺购买参与次数",
	"MountUpgrade"                        =>	"坐骑升阶",
	"MountClearUplevelCD"                 =>	"清理坐骑升级CD",
	"MountQBUpgrade"                      =>	"升级骑兵",
	"StartJilian"                         =>	"祭炼",
	"NeqBuyTimes"                         =>	"装备本购买参与次数",
	"NeqAuto"                             =>	"武器本扫荡花费",
	"NeqRoll"                             =>	"武器本翻牌花费",
	"QunxianluandouRealiveHere"           =>	"群仙乱斗原地复活",
	"PhaseFBAutoFB"                       =>	"阶段本扫荡花费",
	"ExpFBAutoFB"                         =>	"经验本扫荡花费",
	"TeamFBAuto"                          =>	"塔防本扫荡花费",
	"TeamBuyJoinTimes"                    =>	"塔防本购买参与次数",
	"ChallengeFBAutoFB"                   =>	"挑战本扫荡花费",
	"ChallengeFBBuyJoinTimes"             =>	"挑战本购买参与次数",
	"CallGuildMembers"                    =>	"仙盟战召唤仙盟成员",
	"XianmengzhanRealiveHere"             =>	"仙盟战原地复活",
	"GongchengzhanRealiveHere"            =>	"攻城战原地复活",
	"HunyanBless"                         =>	"婚宴祝福花费",
	"GuildPartyOperateDoubleReward"       =>	"仙盟酒会双倍奖励花费",
	"GuildPartyOperateClearGatherCD"      =>	"仙盟酒会清除采集CD",
	"GuildPartyOperateResetGatherCount"   =>	"仙盟酒会重置采集次数",
	"MsgUserHandlerRoleReAlive"           =>	"请求复活协议",
	"SpeakerTalk"                         =>	"传音消耗",
	);

//功能活跃度统计
$ACTIVITY = array(
	'activity_type_0' => '诛邪战场',
	'activity_type_5' => '多人战场',
	'activity_type_6' => '攻城战',
	'activity_type_7' => '仙盟战',
	'activity_type_8' => '全民Boss',
	'fb_type_1' => '装备本',
	'fb_type_2' => '经验本',
	'fb_type_3' => '挑战本',
	'fb_type_4' => '阶段本',
	'fb_type_5' => '塔防本',
	'fb_type_6' => '仙盟神兽本',
	'fb_type_8' => '组队本',
	'fb_type_9' => '情缘本',
	'fb_type_10' => '战神殿副本',
	'HuSongTask' => '护送任务',
	'DailyTask' => '日常任务',
	'GuildTask' => '仙盟任务',
	'QifuBuyCoin' => '祈福铜币',
	'QifuBuyYuanli' => '祈福真气',
	'QifuBuyXianhun' => '祈福仙魂',
	'ChestShop' => '寻宝',
	);

//功能重置命令
$FUN_RESET = array(
	'1'=>'诛邪战场',
	'2'=>'温泉活动',
	'3'=>'答题活动',
	'4'=>'护送活动',
	'5'=>'夺宝仙兵',
	//'6'=>'灵山仙器',
	'7'=>'群仙乱斗',
	'8'=>'仙魔争霸',
	'9'=>'御剑秋名山',
	'10'=>'攻城战',
	'11'=>'仙盟战',
	'12'=>'刺杀城主',
	'13'=>'盗取珍宝',
	'14'=>'魔神降临',
	'15'=>'1V1',
	'16'=>'1VN',
	'3073'=>'跨服1V1',
);

//重置玩家的功能次数
$COUNT_RESET = array(
	'1'=>'仙榜任务',
	'2'=>'屠魔任务',
	'3'=>'护送任务',	
	'4'=>'仙盟任务',	
);

//登录类型
$LOGINTYPE = array(
	'OnGlobalUserLogin' => '登录',
	'OnGlobalUserLogout' => '登出',
);

//军团职位
$POSITION = array(
	'1' => '成员',
	'2' => '护法',
	'3' => '副盟主',
	'4' => '盟主',
);

//副本次数重置
$FB_RESET = array(
	'0' => '须臾幻境',
	'1' => '精英·须臾幻境',
	'2' => '玉石仙府',
	'3' => '四象阵',
	'4' => '仙宠试炼',
	'5' => '精英·神魔之井',
	'6' => '神魔之井',
	'7' => 'VIP副本',
	'8' => '冰火神殿',
	'9' => '精英·冰火神殿',
);

//开启的活动类型
$ACTIVITY_TYPES = array(
	'1025' => '开服冲刺',
	'1026' => '封测活动',
	'1027' => '开服活动',
	'1028' => '通用活动',
	'1029' => '合服活动',
	'1030' => '打怪双倍经验活动',
	'1033' => '世界杯活动',

	'2048' => '随机活动-宝石',
	'2049' => '随机活动-仙女',
	'2050' => '随机活动-坐骑',
	'2051' => '随机活动-强化',
	'2052' => '随机活动-魔卡',
	'2053' => '随机活动-宠物',
	'2054' => '随机活动-累充',
	'2055' => '随机活动-神装',
	'2056' => '随机活动-日充',
	'2057' => '随机活动-百服',
	'2058' => '随机活动-顶级寻宝',
	'10000' => '转盘活动',
);

$BOSS = array(
            30390=>'张梁',
			30391=>'张宝',
			30392=>'张角',
			30590=>'华雄',
			30591=>'陈宫',
			30592=>'徐荣',
			31490=>'貂蝉',
		    31491=>'吕布',
			31492=>'董卓',
			30690=>'文聘',
			30691=>'刑道荣',
			30692=>'刘表',
			30790=>'颜良',
			30791=>'文丑',
			30792=>'审配',
			30793=>'田丰',
			30794=>'甄姬',
			30795=>'袁绍',
);

//背包
$KNAPSACK = array(
	'CertainPut'                 => '物品获取',
	'Use'                        => '物品使用',
	'Discard'                    => '物品丢弃',
	'DiscardEquipment'           => '装备丢弃',
	'ConsumeItem'                => '物品消耗',
	'ConsumeEquipment'           => '装备消耗',
	'ConsumeItemByIndex'         => '物品消耗',
	'ConsumeEquipmentByIndex'    => '装备消耗',
	'ConsumeItemByIndexList'     => '物品批量消耗',
	'ConsumeEquipmentByIndexList'=> '装备批量消耗',
);

//爬塔阶段
$STAGE = array(
	0=>'凡人',
	1=>'武圣',
);

//战场日志
$BATTLE = array(
	'Join'   => '战场报名',
	'Enter'  => '进入战场',
	'Reward' => '战场奖励',
);

//宠物技能
$PETSKILL = array(
					1=>'一阶气血',
					2=>'二阶气血',
					3=>'三阶气血',
					4=>'四阶气血',
					5=>'五阶气血',
					6=>'一阶法力',
					7=>'二阶法力',
					8=>'三阶法力',
					9=>'四阶法力',
					10=>'五阶法力',
					11=>'一阶攻击',
					12=>'二阶攻击',
					13=>'三阶攻击',
					14=>'四阶攻击',
					15=>'五阶攻击',
					16=>'一阶防御',
					17=>'二阶防御',
					18=>'三阶防御',
					19=>'四阶防御',
					20=>'五阶防御',
					21=>'一阶命中',
					22=>'二阶命中',
					23=>'三阶命中',
					24=>'四阶命中',
					25=>'五阶命中',
					26=>'一阶闪避',
					27=>'二阶闪避',
					28=>'三阶闪避',
					29=>'四阶闪避',
					30=>'五阶闪避',
					31=>'一阶破击',
					32=>'二阶破击',
					33=>'三阶破击',
					34=>'四阶破击',
					35=>'五阶破击',
					36=>'一阶抵挡',
					37=>'二阶抵挡',
					38=>'三阶抵挡',
					39=>'四阶抵挡',
					40=>'五阶抵挡',
					41=>'一阶暴击',
					42=>'二阶暴击',
					43=>'三阶暴击',
					44=>'四阶暴击',
					45=>'五阶暴击',
					46=>'一阶坚韧',
					47=>'二阶坚韧',
					48=>'三阶坚韧',
					49=>'四阶坚韧',
					50=>'五阶坚韧',
					51=>'一阶必杀',
					52=>'二阶必杀',
					53=>'三阶必杀',
					54=>'四阶必杀',
					55=>'五阶必杀',
);

//拍卖操作类型
$PUBLICTYPE = array(
	'add'   =>'上架',
	'buy'   =>'购买',
	'overtime_remove'=>'超时下架',
	'user_remove'=>'主动下架',
);
//军团事件
$GUILDEVENT = array(
	'1'=>'创建仙盟',
	'2'=>'加入仙盟',
	'3'=>'退出仙盟',
	'4'=>'踢出仙盟',
	'5'=>'成员任命',
	'6'=>'卸任',
	'7'=>'转让盟主',
	'8'=>'弹劾盟主',
	'9'=>'仙盟升级',
	'10'=>'仓库操作',
	'11'=>'更改权限设置',
	'12'=>'召唤神兽',
	'13'=>'联盟操作',
	'14'=>'喂养神兽',
	'15'=>'成员签到',
	'16'=>'仙盟降级',
	'17'=>'仙盟捐献',
);

//更新配置表
$CONFIG_RESET = array(
	'1'=>'全局',
	'2'=>'技能',
	'3'=>'任务',
	'4'=>'怪物',
	'5'=>'物品',
	'6'=>'逻辑',
	'7'=>'掉落',
	'8'=>'时装',
	'9'=>'场景',
	'128'=>'中心服',
);

//重置仙盟参数
$GUILD_INFO = array(
	'1'=>'仙盟等级',
	'2'=>'仙盟经验',	
	'3'=>'厢房等级',
	'4'=>'神炉等级',
	'5'=>'神炉经验',
	'6'=>'商店等级',
	'7'=>'商店经验',
	'8'=>'仓库等级',	
	'9'=>'仓库经验',
	'10'=>'摇奖等级',
	'11'=>'摇奖经验',
	'12'=>'boss等级',
	'13'=>'boss经验',
);

?>
