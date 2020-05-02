<?php
//活动卡的状态
$card_type["state"] = array(0=>'全新卡', 1=>'GM导出的新卡', 2=>'已使用',3=>'GM导入的新卡');

//360中央后台卡类型转换
$card_type['360'] = array(
	'2'=>2,
	'3'=>51,
);

//活动卡类型和配置
$card_type["type"] = array(
						'0'	=> array(
									"id"=>0,
									"name"=>"新手卡礼包",
									"present"=>array(
													"gold" 		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28090,
													"item_num"  => 1,
													"subject" 	=> "新手卡礼包",
													"content" 	=> "新手卡礼包",
													"days" 		=>"" 		//有效天数
									)
						),
						'1' => array(
									"id"=>1,
									"name"=>"手机卡礼包",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28179,
													"item_num" 	=> 1,
													"subject" 	=> "手机卡礼包",
													"content" 	=> "手机卡礼包",
													"days" 		=>""
									)
						),
						'2' => array(
									"id"=>2,
									"name"=>"YY公民卡",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28180,
													"item_num" 	=> 1,
													"subject" 	=> "YY公民卡",
													"content" 	=> "YY公民卡",
													"days" 		=>""
									)
						),
						'3' => array(
									"id"=>3,
									"name"=>"YY皇室卡",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28181,
													"item_num" 	=> 1,
													"subject" 	=> "YY皇室卡",
													"content" 	=> "YY皇室卡",
													"days" 		=>""
									)
						),
						'4' => array(
									"id"=>4,
									"name"=>"YY会员尊享礼包",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28182,
													"item_num" 	=> 1,
													"subject" 	=> "YY会员尊享礼包",
													"content" 	=> "YY会员尊享礼包",
													"days" 		=>""
									)
						),
						'5' => array(
									"id"=>5,
									"name"=>"YY会员升级礼包",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28183,
													"item_num" 	=> 1,
													"subject" 	=> "YY会员升级礼包",
													"content" 	=> "YY会员升级礼包",
													"days" 		=>""
									)
						),
						'6' => array(
									"id"=>6,
									"name"=>"行会卡",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28184,
													"item_num" 	=> 1,
													"subject" 	=> "行会卡",
													"content" 	=> "行会卡",
													"days" 		=>""
									)
						),
						'7' => array(
									"id"=>7,
									"name"=>"媒体礼包(金)",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28185,
													"item_num" 	=> 1,
													"subject" 	=> "媒体礼包(金)",
													"content" 	=> "媒体礼包(金)",
													"days" 		=>""
									)
						),
						'8' => array(
									"id"=>8,
									"name"=>"媒体礼包(木)",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28186,
													"item_num" 	=> 1,
													"subject" 	=> "媒体礼包(木)",
													"content" 	=> "媒体礼包(木)",
													"days" 		=>""
									)
						),
						'9' => array(
									"id"=>9,
									"name"=>"媒体礼包(水)",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28187,
													"item_num" 	=> 1,
													"subject" 	=> "媒体礼包(水)",
													"content" 	=> "媒体礼包(水)",
													"days" 		=>""
									)
						),
						'10' => array(
									"id"=>10,
									"name"=>"媒体礼包(火)",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28188,
													"item_num" 	=> 1,
													"subject" 	=> "媒体礼包(火)",
													"content" 	=> "媒体礼包(火)",
													"days" 		=>""
									)
						),
						'11' => array(
									"id"=>11,
									"name"=>"媒体礼包(土)",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28189,
													"item_num" 	=> 1,
													"subject" 	=> "媒体礼包(土)",
													"content" 	=> "媒体礼包(土)",
													"days" 		=>""
									)
						),
						'12' => array(
									"id"=>12,
									"name"=>"超级会员生日礼包",
									"present"=>array(
													"gold"		=> 0,
													"gold_bind" => 0,
													"coin" 		=> 0,
													"coin_bind" => 0,
													"item_id"  	=> 28190,
													"item_num" 	=> 1,
													"subject" 	=> "超级会员生日礼包",
													"content" 	=> "超级会员生日礼包",
													"days" 		=>""
									)
						),
						'13' => array(
								"id"=>13,
								"name"=>"超级会员礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28334,
										"item_num" 	=> 1,
										"subject" 	=> "超级会员礼包",
										"content" 	=> "超级会员礼包",
										"days" 		=>""
								)
						),
						'14' => array(
								"id"=>14,
								"name"=>"马上有钱礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28326,
										"item_num" 	=> 1,
										"subject" 	=> "马上有钱礼包",
										"content" 	=> "马上有钱礼包",
										"days" 		=>""
								)
						),
						'15' => array(
								"id"=>15,
								"name"=>"签到礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28327,
										"item_num" 	=> 1,
										"subject" 	=> "签到礼包",
										"content" 	=> "签到礼包",
										"days" 		=>""
								)
						),
						'16' => array(
								"id"=>16,
								"name"=>"论坛签到礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28328,
										"item_num" 	=> 1,
										"subject" 	=> "论坛签到礼包",
										"content" 	=> "论坛签到礼包",
										"days" 		=>""
								)
						),
						'17' => array(
								"id"=>17,
								"name"=>"论坛活跃礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28329,
										"item_num" 	=> 1,
										"subject" 	=> "论坛活跃礼包",
										"content" 	=> "论坛活跃礼包",
										"days" 		=>""
								)
						),
						'18' => array(
								"id"=>18,
								"name"=>"练气礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28330,
										"item_num" 	=> 1,
										"subject" 	=> "练气礼包",
										"content" 	=> "练气礼包",
										"days" 		=>""
								)
						),
						'19' => array(
								"id"=>19,
								"name"=>"筑基礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28331,
										"item_num" 	=> 1,
										"subject" 	=> "筑基礼包",
										"content" 	=> "筑基礼包",
										"days" 		=>""
								)
						),
						'20' => array(
								"id"=>20,
								"name"=>"开光礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28332,
										"item_num" 	=> 1,
										"subject" 	=> "开光礼包",
										"content" 	=> "开光礼包",
										"days" 		=>""
								)
						),
						'21' => array(
								"id"=>21,
								"name"=>"VIP礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28333,
										"item_num" 	=> 1,
										"subject" 	=> "VIP礼包",
										"content" 	=> "VIP礼包",
										"days" 		=>""
								)
						),
						'22' => array(
								"id"=>22,
								"name"=>"微信礼包",
								"present"=>array(
										"gold"		=> 0,
										"gold_bind" => 0,
										"coin" 		=> 0,
										"coin_bind" => 0,
										"item_id"  	=> 28373,
										"item_num" 	=> 1,
										"subject" 	=> "微信礼包",
										"content" 	=> "微信礼包",
										"days" 		=>""
								)
						),
																										
	);
?>