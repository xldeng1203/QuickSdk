<?php
//活動卡的狀態
$card_type["state"] = array(0=>'全新卡', 1=>'GM導出的新卡', 2=>'已使用',3=>'GM導入的新卡');

//360中央後台卡類型轉換
$card_type['360'] = array(
	'2'=>2,
);

//活動卡類型和配置
$card_type["type"] = array(
						'0'	=> array(
									"id"=>0,
									"name"=>"新手卡",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28000,
													"item_num"  => 1,
													"subject" 	=> "新手卡禮包",
													"content" 	=> "新手卡禮包",
													"days" 		=>"" 		//有效天數
									)
						),
						'1' => array(
									"id"=>1,
									"name"=>"遊戲大廳禮包",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28058,
													"item_num" 	=> 1,
													"subject" 	=> "遊戲大廳禮包",
													"content" 	=> "遊戲大廳禮包",
													"days" 		=>""
									)
						),
						'2'=> array(
									"id"=>2,
									"name"=>"360積分商城禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28059,
													"item_num" 	=> 1,
													"subject" 	=> "360積分商城禮包",
													"content" 	=> "360積分商城禮包",
													"days" 		=>""
									)
						),
						'3'=> array(
									"id"=>3,
									"name"=>"手機特權禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28060,
													"item_num" 	=> 1,
													"subject" 	=> "手機特權禮包",
													"content" 	=> "手機特權禮包",
													"days" 		=>""
									)
						),
						'4'=> array(
									"id"=>4,
									"name"=>"武將猜想禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28061,
													"item_num" 	=> 1,
													"subject" 	=> "武將猜想禮包",
													"content" 	=> "武將猜想禮包",
													"days" 		=>""
									)
						),
						'5'=> array(
									"id"=>5,
									"name"=>"黃金特權禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28062,
													"item_num" 	=> 1,
													"subject" 	=> "黃金特權禮包",
													"content" 	=> "黃金特權禮包",
													"days" 		=>""
									)
						),	
						'6'=> array(
									"id"=>6,
									"name"=>"白金特權禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28063,
													"item_num" 	=> 1,
													"subject" 	=> "白金特權禮包",
													"content" 	=> "白金特權禮包",
													"days" 		=>""
									)
						),	
						'7'=> array(
									"id"=>7,
									"name"=>"貴族邀請禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28064,
													"item_num" 	=> 1,
													"subject" 	=> "貴族邀請禮包",
													"content" 	=> "貴族邀請禮包",
													"days" 		=>""
									)
						),
						'8'=> array(
									"id"=>8,
									"name"=>"邀請大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28065,
													"item_num" 	=> 1,
													"subject" 	=> "邀請大禮包",
													"content" 	=> "邀請大禮包",
													"days" 		=>""
									)
						),
						'9'=> array(
									"id"=>9,
									"name"=>"積分商場助力新手禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28066,
													"item_num" 	=> 1,
													"subject" 	=> "積分商場助力新手禮包",
													"content" 	=> "積分商場助力新手禮包",
													"days" 		=>""
									)
						),
						'10'=> array(
									"id"=>10,
									"name"=>"萬人簽名禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28067,
													"item_num" 	=> 1,
													"subject" 	=> "萬人簽名禮包",
													"content" 	=> "萬人簽名禮包",
													"days" 		=>""
									)
						),
						'11'=> array(
									"id"=>11,
									"name"=>"創世三國金幣卡",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28068,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國金幣卡",
													"content" 	=> "創世三國金幣卡",
													"days" 		=>""
									)
						),
						'12'=> array(
									"id"=>12,
									"name"=>"幸運大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28069,
													"item_num" 	=> 1,
													"subject" 	=> "幸運大禮包",
													"content" 	=> "幸運大禮包",
													"days" 		=>""
									)
						),
						'13'=> array(
									"id"=>13,
									"name"=>"強化大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28070,
													"item_num" 	=> 1,
													"subject" 	=> "強化大禮包",
													"content" 	=> "強化大禮包",
													"days" 		=>""
									)
						),
						'14'=> array(
									"id"=>14,
									"name"=>"寵物大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28071,
													"item_num" 	=> 1,
													"subject" 	=> "寵物大禮包",
													"content" 	=> "寵物大禮包",
													"days" 		=>""
									)
						),
						'15'=> array(
									"id"=>15,
									"name"=>"洗鍊大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28072,
													"item_num" 	=> 1,
													"subject" 	=> "洗鍊大禮包",
													"content" 	=> "洗鍊大禮包",
													"days" 		=>""
									)
						),
						'16'=> array(
									"id"=>16,
									"name"=>"貴賓強化禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28073,
													"item_num" 	=> 1,
													"subject" 	=> "貴賓強化禮包",
													"content" 	=> "貴賓強化禮包",
													"days" 		=>""
									)
						),
						'17'=> array(
									"id"=>17,
									"name"=>"貴賓將魂禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28074,
													"item_num" 	=> 1,
													"subject" 	=> "貴賓將魂禮包",
													"content" 	=> "貴賓將魂禮包",
													"days" 		=>""
									)
						),
						'18'=> array(
									"id"=>18,
									"name"=>"貴賓仙寵禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28075,
													"item_num" 	=> 1,
													"subject" 	=> "貴賓仙寵禮包",
													"content" 	=> "貴賓仙寵禮包",
													"days" 		=>""
									)
						),	
						'19'=> array(
									"id"=>19,
									"name"=>"貴賓紫裝禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28076,
													"item_num" 	=> 1,
													"subject" 	=> "貴賓紫裝禮包",
													"content" 	=> "貴賓紫裝禮包",
													"days" 		=>""
									)
						),
						'20'=> array(
									"id"=>20,
									"name"=>"寶石材料大禮包	",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28077,
													"item_num" 	=> 1,
													"subject" 	=> "寶石材料大禮包	",
													"content" 	=> "寶石材料大禮包	",
													"days" 		=>""
									)
						),	
						'21'=> array(
									"id"=>21,
									"name"=>"聖甲大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28253,
													"item_num" 	=> 1,
													"subject" 	=> "聖甲大禮包",
													"content" 	=> "聖甲大禮包",
													"days" 		=>""
									)
						),
						'22'=> array(
									"id"=>22,
									"name"=>"領袖大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28254,
													"item_num" 	=> 1,
													"subject" 	=> "領袖大禮包",
													"content" 	=> "領袖大禮包",
													"days" 		=>""
									)
						),	
						'23'=> array(
									"id"=>23,
									"name"=>"拉票大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28078,
													"item_num" 	=> 1,
													"subject" 	=> "拉票大禮包",
													"content" 	=> "拉票大禮包",
													"days" 		=>""
									)
						),
						'24'=> array(
									"id"=>24,
									"name"=>"創世三國衛士特權禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28079,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國衛士特權禮包",
													"content" 	=> "創世三國衛士特權禮包",
													"days" 		=>""
									)
						),
						'25'=> array(
									"id"=>25,
									"name"=>"YY公民特權",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28080,
													"item_num" 	=> 1,
													"subject" 	=> "YY公民特權",
													"content" 	=> "YY公民特權",
													"days" 		=>""
									)
						),
						'26'=> array(
									"id"=>26,
									"name"=>"YY貴族特權",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28081,
													"item_num" 	=> 1,
													"subject" 	=> "YY貴族特權",
													"content" 	=> "YY貴族特權",
													"days" 		=>""
									)
						),
						'27'=> array(
									"id"=>27,
									"name"=>"YY皇室特權",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28082,
													"item_num" 	=> 1,
													"subject" 	=> "YY皇室特權",
													"content" 	=> "YY皇室特權",
													"days" 		=>""
									)
						),
						'28'=> array(
									"id"=>28,
									"name"=>"強化幸運包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28083,
													"item_num" 	=> 1,
													"subject" 	=> "強化幸運包",
													"content" 	=> "強化幸運包",
													"days" 		=>""
									)
						),
						'29'=> array(
									"id"=>29,
									"name"=>"寵物提升包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28084,
													"item_num" 	=> 1,
													"subject" 	=> "寵物提升包",
													"content" 	=> "寵物提升包",
													"days" 		=>""
									)
						),	
						'30'=> array(
									"id"=>30,
									"name"=>"快速升等包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28085,
													"item_num" 	=> 1,
													"subject" 	=> "快速升等包",
													"content" 	=> "快速升等包",
													"days" 		=>""
									)
						),
						'31'=> array(
									"id"=>31,
									"name"=>"氣血法力包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28086,
													"item_num" 	=> 1,
													"subject" 	=> "氣血法力包",
													"content" 	=> "氣血法力包",
													"days" 		=>""
									)
						),	
						'32'=> array(
									"id"=>32,
									"name"=>"富貴逼人包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28087,
													"item_num" 	=> 1,
													"subject" 	=> "富貴逼人包",
													"content" 	=> "富貴逼人包",
													"days" 		=>""
									)
						),	
						'33'=> array(
									"id"=>33,
									"name"=>"三國鬥殺包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28088,
													"item_num" 	=> 1,
													"subject" 	=> "三國鬥殺包",
													"content" 	=> "三國鬥殺包",
													"days" 		=>""
									)
						),
						'34'=> array(
									"id"=>34,
									"name"=>"FB按贊禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28089,
													"item_num" 	=> 1,
													"subject" 	=> "FB按贊禮包",
													"content" 	=> "FB按贊禮包",
													"days" 		=>""
									)
						),	
						'35'=> array(
									"id"=>35,
									"name"=>"07073黃金禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28267,
													"item_num" 	=> 1,
													"subject" 	=> "07073黃金禮包",
													"content" 	=> "07073黃金禮包",
													"days" 		=>""
									)
						),
						'36'=> array(
									"id"=>36,
									"name"=>"07073水晶禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28268,
													"item_num" 	=> 1,
													"subject" 	=> "07073水晶禮包",
													"content" 	=> "07073水晶禮包",
													"days" 		=>""
									)
						),
						'37'=> array(
									"id"=>37,
									"name"=>"07073鑽石禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28269,
													"item_num" 	=> 1,
													"subject" 	=> "07073鑽石禮包",
													"content" 	=> "07073鑽石禮包",
													"days" 		=>""
									)
						),
						'38'=> array(
									"id"=>38,
									"name"=>"265g獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28270,
													"item_num" 	=> 1,
													"subject" 	=> "265g獨家禮包",
													"content" 	=> "265g獨家禮包",
													"days" 		=>""
									)
						),
						'39'=> array(
									"id"=>39,
									"name"=>"17173獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28271,
													"item_num" 	=> 1,
													"subject" 	=> "17173獨家禮包",
													"content" 	=> "17173獨家禮包",
													"days" 		=>""
									)
						),
						'40'=> array(
									"id"=>40,
									"name"=>"多玩獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28272,
													"item_num" 	=> 1,
													"subject" 	=> "多玩獨家禮包",
													"content" 	=> "多玩獨家禮包",
													"days" 		=>""
									)
						),
						'41'=> array(
									"id"=>41,
									"name"=>"969g獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28273,
													"item_num" 	=> 1,
													"subject" 	=> "969g獨家禮包",
													"content" 	=> "969g獨家禮包",
													"days" 		=>""
									)
						),
						'42'=> array(
									"id"=>42,
									"name"=>"通發媒體禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28274,
													"item_num" 	=> 1,
													"subject" 	=> "通發媒體禮包",
													"content" 	=> "通發媒體禮包",
													"days" 		=>""
									)
						),
						'43'=> array(
									"id"=>43,
									"name"=>"新浪獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28275,
													"item_num" 	=> 1,
													"subject" 	=> "新浪獨家禮包",
													"content" 	=> "新浪獨家禮包",
													"days" 		=>""
									)
						),
						'44'=> array(
									"id"=>44,
									"name"=>"微遊戲大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28276,
													"item_num" 	=> 1,
													"subject" 	=> "微遊戲大禮包",
													"content" 	=> "微遊戲大禮包",
													"days" 		=>""
									)
						),
						'45'=> array(
									"id"=>45,
									"name"=>"YY活動禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28277,
													"item_num" 	=> 1,
													"subject" 	=> "YY活動禮包",
													"content" 	=> "YY活動禮包",
													"days" 		=>""
									)
						),	
						'46'=> array(
									"id"=>46,
									"name"=>"微博活動大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28278,
													"item_num" 	=> 1,
													"subject" 	=> "微博活動大禮包",
													"content" 	=> "微博活動大禮包",
													"days" 		=>""
									)
						),
						'47'=> array(
									"id"=>47,
									"name"=>"玩玩活動大禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28279,
													"item_num" 	=> 1,
													"subject" 	=> "玩玩活動大禮包",
													"content" 	=> "玩玩活動大禮包",
													"days" 		=>""
									)
						),
						'48'=> array(
									"id"=>48,
									"name"=>"桃園結義禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28280,
													"item_num" 	=> 1,
													"subject" 	=> "桃園結義禮包",
													"content" 	=> "桃園結義禮包",
													"days" 		=>""
									)
						),	
						'49'=> array(
									"id"=>49,
									"name"=>"群雄逐鹿禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28281,
													"item_num" 	=> 1,
													"subject" 	=> "群雄逐鹿禮包",
													"content" 	=> "群雄逐鹿禮包",
													"days" 		=>""
									)
						),
						'50'=> array(
									"id"=>50,
									"name"=>"天下三分禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28282,
													"item_num" 	=> 1,
													"subject" 	=> "天下三分禮包",
													"content" 	=> "天下三分禮包",
													"days" 		=>""
									)
						),
						'51'=> array(
									"id"=>51,
									"name"=>"手機綁定禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28245,
													"item_num" 	=> 1,
													"subject" 	=> "手機綁定禮包",
													"content" 	=> "手機綁定禮包",
													"days" 		=>""
									)
						),
						'52'=> array(
									"id"=>52,
									"name"=>"創世三國新服禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28300,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國新服禮包",
													"content" 	=> "創世三國新服禮包",
													"days" 		=>""
									)
						),	
						'53'=> array(
									"id"=>53,
									"name"=>"創世三國魔法禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28301,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國魔法禮包",
													"content" 	=> "創世三國魔法禮包",
													"days" 		=>""
									)
						),	
						'54'=> array(
									"id"=>54,
									"name"=>"創世三國至尊禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28302,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國至尊禮包",
													"content" 	=> "創世三國至尊禮包",
													"days" 		=>""
									)
						),
						'55'=> array(
									"id"=>55,
									"name"=>"創世三國安仔寵物禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28303,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國安仔寵物禮包",
													"content" 	=> "創世三國安仔寵物禮包",
													"days" 		=>""
									)
						),
						'56'=> array(
									"id"=>56,
									"name"=>"2366獨家禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28304,
													"item_num" 	=> 1,
													"subject" 	=> "2366獨家禮包",
													"content" 	=> "2366獨家禮包",
													"days" 		=>""
									)
						),
						'57'=> array(
									"id"=>57,
									"name"=>"YY金蛇禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28307,
													"item_num" 	=> 1,
													"subject" 	=> "YY金蛇禮包",
													"content" 	=> "YY金蛇禮包",
													"days" 		=>""
									)
						),
						'58'=> array(
									"id"=>58,
									"name"=>"創世三國新春禮包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28309,
													"item_num" 	=> 1,
													"subject" 	=> "創世三國新春禮包",
													"content" 	=> "創世三國新春禮包",
													"days" 		=>""
									)
						),																																																																																																																																																																																																																																																																																																						
																										
	);
?>