<?php
class api extends cls_control
{
	/**
	 * 充值API
	 *
	 * @param array $post
	 * @return array
	 */
	public function pay($post){
		//请求失败日志
		$errorlog = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$post['user'].'_'.$post['sid']."\tip:".$post['ip']
				."\tpaynum:".$post['paynum']."\tmode:".$post['mode']."\tmoney:".$post['money']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s", $post['time'])."]"
				."\tticket:".$post['ticket']."\tref:".$post['referer']."\thost:".$post['host']."\tquery:".$post['query']."\tresult:";

		if(!isset($post['sid']) || empty($post['paynum']) || empty($post['mode']) || empty($post['user']) || !isset($post['money']) || empty($post['gold']) || empty($post['time']) || empty($post['ticket'])){
			//充值失败写入日志
			$errorlog .= $post['result']['exile_parameter']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']['exile_parameter']);//参数不齐全
		}
		//验证IP是否指定IP
/*		if(!in_array($post['ip'], $post['allow_ip'])){
			//充值失败写入日志
			$errorlog .= $post['result']['ip_error']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']['ip_error']);//IP非法
		}*/
		//加密串验证
		$md5_key = md5($post['paynum'].$post['user'].$post['money'].$post['gold'].$post['time'].$post['md5_key']);
		if($md5_key != $post['ticket']){
			//充值失败写入日志
			$errorlog .= $post['result']['md5_key_error']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']['md5_key_error']);//密钥错误
		}

		//验证超时,设置2分钟超时
		if(THIS_DATETIME - $post['time'] > 120){
			//充值失败写入日志
			$errorlog .= $post['result']['request_timed_out']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']['request_timed_out']);//连接超时
		}

		$plat_user_name = $post['user'].'_'.intval($post['sid']);//平台名由用户名加服ID
		//查询是否存在该玩家信息
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','role_id,role_name');
		if(empty($roleinfo)){
			//充值失败写入日志
			$errorlog .= $post['result']['user_not_exist']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']["user_not_exist"]);//用户不存在
		}
		$role_id = $roleinfo[0]['role_id'];
		//获取玩家的充值等级
		$lvRes = $this->load('cls_role','mmoarpg')->records(array('field'=>'role_id','val'=>$role_id),'','','','','','level');
		if(!empty($lvRes)){
			$lv = $lvRes[0]['level'];
		}else{
			$lv = 1;  //无法查询到玩家的等级，此种情况应该不存在
		}		
		$role_name = $roleinfo[0]['role_name'];
		//检查订单的唯一性
		$totalcharge = $this->load('cls_charge','admintool')->records(array('field'=>'paynum','val'=>$post['paynum']),'','','','',true);
		if($totalcharge){
			//充值失败写入日志
			$errorlog .= $post['result']['order_repeat']."\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['result']['order_repeat']);
		}
		//查询元宝表
		$accountinfo = $this->load('cls_accountgold','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','gold,gold_history');
		$log = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$plat_user_name."\trole_id:".$role_id."\trole_name:".$role_name."\tip:".$post['ip']
				."\tpaynum:".$post['paynum']."\tmode:".$post['mode']."\tmoney:".$post['money']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s", $post['time'])."]"
				."\tticket:".$post['ticket']."\tref:".$post['referer']."\thost:".$post['host']."\tquery:".$post['query']."\tresult:";
		if(empty($accountinfo)){//如果不存在充值记录，则新插入
			$paccount = array(
				'plat_user_name'=>$plat_user_name,
				'gold'=>$post['gold'],
				'gold_history'=>$post['gold'],
				'last_get_gold_time'=>0,
			);
			if(!$post['money']){
				$paccount['authority_type']=1;
			}
			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$insert = $this->load('cls_accountgold','mmoarpg')->insert($paccount);
			if(!$insert){
				//充值失败写入日志，回滚之前的操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= $post['result']['charge_fail']."\r\n";
				$this->error($log,'api.pay.'.date('Ymd'),0,'pay/');
				return $this->result(0,$post['result']['charge_fail']); //充值失败
			}
		}else{//否则，更新记录
			$paccount = array(
			   'gold'=>$accountinfo[0]['gold'] + intval($post['gold']),//服务器响应后会清为0，但是为了防止连续充值，需要用原来的值加上新的元宝
			   'gold_history'=>$accountinfo[0]['gold_history'] + intval($post['gold']),
			);
			if(!$post['money']){
				$paccount['authority_type']=1;
			}
			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$update = $this->load('cls_accountgold','mmoarpg')->update($paccount,array('field'=>'plat_user_name','val'=>$plat_user_name));
			if(!$update){
				//充值失败写入日志，回滚之前的操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= $post['result']['charge_fail']."\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.fail',0,'pay/');
				return $this->result(0,$post['result']['charge_fail']); //充值失败
			}
		}
			//充值成功，插入充值记录到后台充值订单表
			$pcharge = array(
								'server_id' => intval($post['sid']),        //服ID
								'paynum'	=> $post['paynum'], 	//订单号
								'mode'		=> $post['mode'], 		//充值方式
								'user'		=> $post['user'], 	//充值平台帐号
								'role_id'	=> $role_id, 			//角色ID
								'role_name' => $role_name, 			//角色名称
								'money'		=> floatval($post['money']), 		//充值金额
								'gold'		=> intval($post['gold']),		//充值元宝
								'time'		=> $post['time'], 		//充值时间
								'ticket'	=> $post['ticket'], 	//充值验证串
								'ip'		=> $post['ip'], 		//充值IP
								'result'	=> $post['result']['true'],	//充值结果
								'lv'        => $lv,  //充值角色等级
			);
			//查看订单是否存在，若是不存在则执行插入操作，否则回滚之前的操作
			$totalcharge = $this->load('cls_charge','admintool')->records(array('field'=>'paynum','val'=>$post['paynum']),'','','','',true);
			if($totalcharge){
				//回滚操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				//充值失败写入日志
				$errorlog .= "\t失败原因：订单重复 \r\n";
				$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
				return $this->result(0,'2');   //订单重复了
			}
			$pinsert = $this->load('cls_charge','admintool')->insert($pcharge);
			if(!$pinsert){
				$log .= "后台生成订单失败\tvalue:".var_export($pcharge,true)."\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
				//订单生成失败，回滚操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				return $this->result(0,'0'); //充值失败
			}			
			//插入命令，优化充值后到账时间
			$pcommand = array(
			    'creator' => 'background',		// 后台
				'createtime' => THIS_DATETIME,
				'type'	=> 2,					// loginserver
				'cmd' => "newnotice 3 {$role_id}",
			);
			$cinsert = $this->load('cls_command','mmoarpg')->insert($pcommand);
			//订单生成成功，执行事务
			$this->load('cls_accountgold','mmoarpg')->commit();
			$log .= $post['result']['true']."\r\n";
			$this->error($log,'api.pay.'.date('Ymd').'.succ',0,'pay/');
		    return $this->result(1,'','',$post['result']['true']);
	}
	
	
	/**
	 * 玩家反馈接口
	 *
	 * @param array $post
	 * @return array
	 */
	public function feedback($post){
		if(empty($post['plat_user_name']) || empty($post['role_id']) || empty($post['role_name']) || !isset($post['type']) || empty($post['title']) || empty($post['content']) || empty($post['ticket'])){
			return $this->result(0,'0');//参数不齐全
		}

		//加密串验证
		$md5_key = md5($post['plat_user_name'].$post['type'].$post['title'].$post['time'].$post['md5_key']);
		if($md5_key != $post['ticket']){
			return $this->result(0,'1');//密钥错误
		}
		
		//查询玩家是否充值过，充值过的是VIP玩家
		$charge = $this->load('cls_charge','admintool')->records(array('field'=>'user','val'=>$post['plat_user_name']),'','','','',true);
		if($charge){
			$vip = 1;
		}else{
			$vip = 0;
		}
		$fpost = array(
					'plat_user_name'=>$post['plat_user_name'],
					'role_id'=>$post['role_id'],
					'role_name'=>$post['role_name'],
					'is_vip'=>$vip,
					'type'=>$post['type'],
					'title'=>$post['title'],
					'content'=>$post['content'],
					'con_type'=>$post['con_type'],
					'con_content'=>$post['con_content'],
					'occur_time'=>$post['time'],
					'create_time'=>time(),
					'state'=>0,
		);
		$insert = $this->load('cls_playerfeed','admintool')->insert($fpost);
		if(!$insert){
			return $this->result(0,'2');//失败返回
		}else{
			return $this->result(1,'','','3');//成功返回
		}
		
	}
	
	/**
	 * 客户端禁言
	 *
	 * @param array $post
	 * @return array
	 */
	public function playerOption($post){
		if(empty($post['role_id']) || empty($post['ticket']) || empty($post['type'])){
			return $this->result(0,'-1');//参数不齐全
		}
		if($post['type'] == 'mute'){
			if(empty($post['shut_time'])){
				return $this->result(0,'-1');
			}
		}

		//加密串验证
		$md5_key = md5($post['type'].$post['md5_key'].$post['role_id']);
		if($md5_key != $post['ticket']){
			return $this->result(0,'-2');//密钥错误
		}
		
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'role_id','val'=>$post['role_id']),'','','','','','role_name,plat_user_name');
		if(!empty($roleinfo)){
			$plat_user_name = $roleinfo[0]['plat_user_name'];
			$role_name  = $roleinfo[0]['role_name'];
		}else{
			return $this->result(0,'-4');//玩家不存在
		}
		//禁言
		switch ($post['type']){
			case 'mute':
				$cmd = "Mute {$post['role_id']} {$post['shut_time']}";
				$cpost = array(
			          'creator'=>'client',
					  'createtime'=> THIS_DATETIME,
					  'type'=> 2,
					  'cmd'=>$cmd,
				);
				$insert = $this->load('cls_command','mmoarpg')->insert($cpost);
				if($insert){
				    $log = "添加禁言命令：[创建者：client,角色ID：{$post['role_id']},角色名：{$role_name},禁言持续时间：".$post['shut_time']."]";
				    $this->error($log,'api.mute.'.date('Ymd').'.succ',0,'mute/');
				    return $this->result(1,'','','1');//成功返回1
				}else{
					return $this->result(0,'-3');     //操作失败
				}				
				break;
			case 'forbid':
				$forbid_time = 31536000;
				$cmd = "Forbid {$plat_user_name} $forbid_time";
				$cpost = array(
			          'creator'=>'client',
					  'createtime'=> THIS_DATETIME,
					  'type'=> 1,
					  'cmd'=>$cmd,
				);
				$insert = $this->load('cls_command','mmoarpg')->insert($cpost);
				if($insert){
				    $log = "添加封号命令：[创建者：client,角色ID：{$post['role_id']},角色名：{$role_name},封号持续时间：".$forbid_time."]";
				    $this->error($log,'api.forbid.'.date('Ymd').'.succ',0,'forbid/');
				    return $this->result(1,'','','1');//成功返回1
				}else{
					return $this->result(0,'-3');     //操作失败
				}				
				break;
			case 'kickout':
				$cmd = "CmdToRoleKickOut {$post['role_id']}";
				$cpost = array(
			          'creator'=>'client',
					  'createtime'=> THIS_DATETIME,
					  'type'=> 2,
					  'cmd'=>$cmd,
				);
				$insert = $this->load('cls_command','mmoarpg')->insert($cpost);
				if($insert){
				    $log = "添加踢下线命令：[创建者：client,角色ID：{$post['role_id']},角色名：{$role_name}]";
				    $this->error($log,'api.kickout.'.date('Ymd').'.succ',0,'kickout/');
				    return $this->result(1,'','','1');//成功返回1
				}else{
					return $this->result(0,'-3');     //操作失败
				}				
				break;
			default:
				return $this->result(0,'-3');
				break;								
		}
		


	}
	
	/**
	 * 指定类型返回个人排行榜数据
	 *
	 * @param array $post
	 * @return array
	 */
	public function personrank($post){
		if(empty($post['time']) || empty($post['ticket'])){
			return $this->result(0,'0');//参数不齐全
		}

		//加密串验证
		$md5_key = md5($post['time'].$post['md5_key']);
		if($md5_key != $post['ticket']){
			return $this->result(0,'1');//密钥错误
		}
		
		$where = array('field'=>'rank_type','val'=>$post['type']);
		$orderby = array('field'=>'rank_value','type'=>'desc');
        $rank = array();
		$rank = $this->load('cls_personrank','mmoarpg')->records($where,$orderby,'',1,$post['num'],'','distinct(role_id),rank_value,user_name');
		if(!empty($rank)){
            return $this->result(1,'','',$rank);	
        }else{
        	return $this->result(0);
        }
	}
	
	
	/**
	 * 通过平台名查询玩家信息
	 *
	 * @param array $post
	 * @return array
	 */
	public function getroleinfo($post){
		if(empty($post['time']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);//参数不齐全
		}

		//加密串验证
		$md5_key = md5($post['username'].$post['time'].$post['md5_key']);
		if($md5_key != $post['ticket']){
			return $this->result(0,$post['result']['ticket_error']);//密钥错误
		}
		
		$plat_user_name = $post['username'].'_'.$post['server'];//暂时由服ID组成，合服后会出问题。
		$platuser = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','role_id');
		
		if(empty($platuser)){
			return $this->result(0,$post['result']['user_name_error']);//用户名不存在
		}
		$role_id = $platuser[0]['role_id'];
		$roleinfo = $this->load('cls_role','mmoarpg')->records(array('field'=>'role_id','val'=>$role_id),'','','','','','role_id,role_name,online_time,last_save_time');
		$roleattrinfo = $this->load('cls_role_attr_detail','mmoarpg')->records(array('field'=>'role_id','val'=>$role_id),'','','','','','camp,prof,level,gold,gold_bind,coin,coin_bind,guild_name');
		$data = array();
		$data['plat_user_name'] = $plat_user_name;
		$data['role_name'] 		= $roleinfo[0]['role_name'];
		$data['is_online'] 		= ($roleinfo[0]['last_save_time']+300 > THIS_DATETIME) ? '在线' : '离线';
		$data['online_time'] 	= $roleinfo[0]['online_time'];
		$data['country'] 		= $post['country'][$roleattrinfo[0]['camp']];
		$data['professional'] 	= $post['professional'][$roleattrinfo[0]['prof']];
		$data['level'] 			= $roleattrinfo[0]['level'];
		$data['gold'] 			= $roleattrinfo[0]['gold'];
		$data['gold_bind'] 		= $roleattrinfo[0]['gold_bind'];
		$data['coin'] 			= $roleattrinfo[0]['coin'];
		$data['coin_bind'] 		= $roleattrinfo[0]['coin_bind'];
		$data['guild_name'] 		= $roleattrinfo[0]['guild_name'];
		return $this->result(1,'','',$data);
	}
	
	/**
	 * 客户端领取礼包接口
	 *
	 * @param array $post
	 * @return array
	 */
	public function usecard($post){
		if(empty($post['card']) || empty($post['username']) || empty($post['role_id']) || empty($post['role_name']) || empty($post['time']) || empty($post['ticket'])){
			return $this->result(0,'0');//参数不齐全
		}
		
		//设置5分钟连接超时时间
		if(THIS_DATETIME - $post['time'] > 300){
			return $this->result(0,'1');//连接超时
		}

		//加密串验证
		$md5_key = md5($post['card'].$post['sid'].$post['username'].$post['role_id'].$post['time'].$post['md5_key']);
		if($md5_key != $post['ticket']){
			return $this->result(0,'2');//密钥错误
		}
		
		if(strlen(trim($post['card'])) < 32){
			return $this->result(0,'3'); //卡号长度不对，卡号是32位MD5加密串
		}		
		
		$tid = 0;//设置默认卡类型，用来区分手机绑定类型
		
		if($post['platname'] == '4399'){//4399平台手机绑定礼包
			//code = md5(key + game + server + username + type)
			//首先判断是否为手机绑定礼包
			$code = md5($post['plat_key'].'cssgS'.$post['sid'].$post['username'].'sj');
			if($post['card'] == $code){
				$tid = 51;
			}
		}
		
		if($tid){//如果是手机绑定类型
			$ctype = $tid;
		}else{
			//根据卡号长度来判断是否为幸运转盘抽奖礼包
			if(strlen(trim($post['card'])) == 34){
				$type = substr($post['card'], 0,2);
				$key  = substr($post['card'], 2,34);
				$turnplatekey = md5("{$post['username']}|{$type}|84D988A6-79F3-3360-01CN-29WAN360MALL");
				if($key != $turnplatekey){
					//创世三国卫士特权礼包
					$prekey = md5("{$post['username']}|98|cssg");
					if($type != '98' || $prekey != $key){					
						return $this->result(0,'4');  //卡号不存在
					}
				}
								
				$PLATE = array(
					'01' => '16',
					'02' => '17',
					'03' => '18',
					'04' => '14',
					'05' => '13',
					'06' => '20',
					'07' => '12',
					'08' => '19',
					'09' => '15',
					'98' => '60',
				);
				$ctype = $PLATE[$type];
				$plate_card = 1;
				if(!$ctype){
					return $this->result(0,'4');
				}
				//查看该卡号是否已经存在，如果存在也直接退出
				$usecard = $this->load('cls_present_card')->records(array(array('field'=>'card','val'=>$post['card']),array('field'=>'type','val'=>$ctype)),'','','','',true);
				if(!empty($usecard)){
					return $this->result(0,'5');
				}
			}else{
				//如果是新手卡为自动生成方式，先判断是否为新手卡
				if($post['getcard'] == 2){
					//判断卡号是否可用
					//md5($qid.$server_id.$key); 
					//必须根据平台来区分加密方式
					switch ($post['platname']){
						case '4399':
							$card = md5($post['plat_key'].$post['username']);
							break;
						default:
							$card = md5($post['username'].'S'.$post['sid'].$post['plat_key']);
							//转换为大写
							$card = strtoupper($card);
							break;
					}					
					//如果卡号正确
					if($card == $post['card']){
						$ctype = 0;    //新手卡类型ID为0					
					}
					//否则如果卡号不正确，查询是否为其它卡
					else{
						$cardinfo = $this->load('cls_present_card','admintool')->records(array('field'=>'card','val'=>$post['card']),'','','','','','use_time,type');
						if(empty($cardinfo) || $cardinfo[0]['use_time'] > 0){
							return $this->result(0,'4');//卡号不可以使用
						}
						$ctype = $cardinfo[0]['type'];
						//新手卡必须是平台生成，而并非后台生成。
						if($ctype == '0'){
							return $this->result(0,'4');
						}										
					}				
				}
				//非新手卡或者新手卡为发放形式
				else{
					$cardinfo = $this->load('cls_present_card','admintool')->records(array('field'=>'card','val'=>$post['card']),'','','','','','use_time,type');
					if(empty($cardinfo) || $cardinfo[0]['use_time'] > 0){
						return $this->result(0,'4');//卡号不可以使用
					}
					$ctype = $cardinfo[0]['type'];
				}
			}			
		}
		
		//平台账号
		$plat_user_name = $post['username'].'_'.$post['sid'];
		$usecard = $this->load('cls_present_card')->records(array(array('field'=>'use_plat_user_name','val'=>$plat_user_name),array('field'=>'type','val'=>$ctype)),'','','','',true);
		if($usecard){
			return $this->result(0,'5');//用户已经领取过礼包，不可重复领取
		}
		
		$gold 		 = $post['cardtype'][$ctype]['present']['gold'];			//元宝
		$gold_bind 	 = $post['cardtype'][$ctype]['present']['gold_bind'];		//绑定元宝
		$coin 		 = $post['cardtype'][$ctype]['present']['coin'];			//银两
		$coin_bind 	 = $post['cardtype'][$ctype]['present']['coin_bind'];		//绑定银两
		$item_id 	 = $post['cardtype'][$ctype]['present']['item_id'];			//赠送物品ID或者礼包ID
		$item_num 	 = $post['cardtype'][$ctype]['present']['item_num'];		//赠送物品数量或者礼包数量
		$subject 	 = $post['cardtype'][$ctype]['present']['subject'];			//邮件标题
		$content 	 = $post['cardtype'][$ctype]['present']['content'];			//邮件内容
		$dead_line   = empty($post['cardtype'][$ctype]['present']['days']) ? '' : $post['time'] + 86400 * $post['cardtype'][$ctype]['present']['days']; //过期时间
		$p = array(
						'uid'=>$post['role_id'], 								//角色ID
						'recv_time'=>THIS_DATETIME, 								//收到邮件的时间
						'kind'=>2, 													//邮件类型，2为系统邮件
						'coin'=>$coin, 												//赠送银两
						'coin_bind'=>$coin_bind, 									//赠送的绑定银两
						'gold'=>$gold,
						'gold_bind'=>$gold_bind,
						'item_id1'=>$item_id, 										//物品ID
						'item_num1'=>$item_num,	 									//物品数量
						'item_invalid_time1'=>$dead_line, 							//过期时间
						'subject'=>$subject, 										//邮件主题
						'content'=>$content, 										//邮件内容
			);
		
		//开启事务
		$this->load('cls_systemmail','mmoarpg')->setAutoCommit();
		$pinsert = $this->load('cls_systemmail','mmoarpg')->insert($p);//赠送物品邮件
        if($tid || ($ctype == 0 && $post['getcard'] == 2) || $plate_card){//如果是手机绑定类型或者如果是新手卡并且获取类型为自动生成方式的话就插入操作
        	$ipost = array(
        	            'type'=>$ctype,
	    	            'card'=>$post['card'],
	    	            'state'=>2,
						'use_plat_user_name'=>$plat_user_name,
						'role_id'=>$post['role_id'],
						'role_name'=>$post['role_name'],
						'use_time'=>$post['time'],
        	);
        	//查看卡是否已经被领取，若是没被领取则执行插入操作，否则回滚之前的操作
        	$usecard = $this->load('cls_present_card')->records(array(array('field'=>'role_id','val'=>$post['role_id']),array('field'=>'type','val'=>$ctype)),'','','','',true);
			if($usecard){
				//回滚操作
				$this->load('cls_systemmail','mmoarpg')->rollback();
				return $this->result(0,'5');//用户已经领取过礼包，不可重复领取
			}
        	$insert = $this->load('cls_present_card','admintool')->insert($ipost);
        	if($insert){
        		//插入成功，提交事务
        		$this->load('cls_systemmail','mmoarpg')->commit();
        	}else{
        		//插入失败，回滚事务
        		$this->load('cls_systemmail','mmoarpg')->rollback();
				return $this->result(0,'1');//领取失败
        	}        	
		    
        }else{//否则更新旧的数据
        	$upost = array(
        	            'state'=>2,
						'use_plat_user_name'=>$plat_user_name,
						'role_id'=>$post['role_id'],
						'role_name'=>$post['role_name'],
						'use_time'=>$post['time'],
		    );
            //查看卡是否已经被领取，若是没被领取则执行更新操作，否则回滚之前的操作
        	$usecard = $this->load('cls_present_card')->records(array(array('field'=>'role_id','val'=>$post['role_id']),array('field'=>'type','val'=>$ctype)),'','','','',true);
			if($usecard){
				//回滚操作
				$this->load('cls_systemmail','mmoarpg')->rollback();
				return $this->result(0,'5');//用户已经领取过礼包，不可重复领取
			}		    
		    $update = $this->load('cls_present_card','admintool')->update($upost,array('field'=>'card','val'=>$post['card']));
        	if($update){
        		//更新成功，提交事务
        		$this->load('cls_systemmail','mmoarpg')->commit();
        	}else{
        		//更新失败，回滚事务
        		$this->load('cls_systemmail','mmoarpg')->rollback();
				return $this->result(0,'1');//领取失败
        	}         
        }
        //执行插入命令，通知后台及时发送邮件
        $command = array(
				       'creator' => 'background',		// 后台
				       'createtime' => time(),			//
				       'type' => 2,					// loginserver
				       'cmd' => "newnotice 2 {$post['role_id']}",
	
				   );
		cls_entry::load('command')->add($command);//插入命令行
		$log = "[".date("Y-m-d H:i:s")."]"
                   ."\tplat_user_name:".$plat_user_name
                   ."\trole_name:".$post['role_name']
                   ."\trole_id:".$post['role_id']
                   ."\ts_id:".$post['sid']
                   ."\tgold:".$gold
                   ."\tgold_bind:".$gold_bind
                   ."\tcoin:".$coin
                   ."\tcoin_bind:".$coin_bind
                   ."\titem_id:".$item_id
                   ."\titem_num:".$item_num
                   ."\tsubject:".$subject
                   ."\tcontent:".$content
                   ."\tcreate_time:".THIS_DATETIME
                   ."\tdead_line:".$dead_line
                   ."\r\n";
        $this->error($log,'api.present.'.date('Ymd').'.succ',0,'present/');
        return $this->result(1,'','','6');
	}


	
	/**
	 * 注册流失率统计入库
	 *
	 * @param array $post
	 * @return array
	 */
	public function registerstatis($post){
		if($post['pname'] == ''){
			return $this->result(0,'0');// 平台名为空。平台名允许为0,但是不允许为空
		}
		if(empty($post['ticket'])){
			return $this->result(0,'1');// 密钥为空
		}
		if(empty($post['state']) || $post['state'] < 1 || $post['state'] > 8){
			return $this->result(0,'2'); // state参数非法
		}

		$ticket = md5($post['state'].$post['pname'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,'3');// 密钥验证错误
		}
		$plat_user_name = $post['pname'].'_'.$post['sid'];
		$registerinfo = $this->load('cls_log_register','admintool')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','conn_num,entergame_num,unconn_server_num');
		if(!empty($registerinfo)){
			$isexist = 1;
			$conn_num = $registerinfo[0]['conn_num'];
			$entergame_num = $registerinfo[0]['entergame_num'];
			$unconn_server_num = $registerinfo[0]['unconn_server_num'];
		}else{
			$isexist = 0;
		}
		$where = array('field'=>'plat_user_name','val'=>$plat_user_name);
		switch ($post['state']){
			case 1://进入加载资源loading界面
				if($isexist){
					$upost = array('last_loading_time'=>THIS_DATETIME, 'conn_num'=>$conn_num+1, 'last_conn_result'=>0, 'last_login_ip'=>$post['ip']);
					$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				}else{
					$ipost = array(
							  'user_type'=>$post['user_type'],
							  'plat_user_name'=>$plat_user_name,
							  'first_loading_time'=>THIS_DATETIME,
							  'last_loading_time'=>THIS_DATETIME,
							  'conn_num'=>1,
							  'last_conn_result'=>0,
							  'first_login_ip'=>$post['ip'],
							  'last_login_ip'=>$post['ip'],
							  'load_flash_finish'=>$post['load_flash'],
					);
					$insert = $this->load('cls_log_register','admintool')->insert($ipost);
					return $this->result(1,'','','4');//第一次加载游戏，让客户端返回加载flash的情况和玩家创建完角色进入游戏过程的行为
				}
				break;
			case 2://flash加载完成
				if($post['first_load']){
					$upost = array(
					         'first_loading_finish_time'=>THIS_DATETIME,
					         'last_loading_finish_time'=>THIS_DATETIME,
					         'load_flash_finish'=>$post['load_flash'],
					);
				}else{
					$upost = array(
					         'last_loading_finish_time'=>THIS_DATETIME,
					);
				}
				$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				break;
			case 3://进入角色创建界面，走到这步，flash未必全部都加载完成。
				$upost = array('create_time'=> THIS_DATETIME);
				$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				break;
			case 4://产生用户行为
				if($post['first_load']){
					$upost = array(	//记录用户行为
						'user_doings_time'=>THIS_DATETIME,
						'click_sex'=>$post['click_sex'],
						'click_dice'=>$post['click_dice'],
						'rewrite_role_name'=>$post['rewrite_name'],
						'entergame_type'=>$post['entergame_type']
				   );
				   $update = $this->load('cls_log_register','admintool')->update($upost,$where);
				}
				break;
			case 5://创建角色完成
				$res = $this->load('cls_log_register','admintool')->records($where,'','','','','','create_finish_time');
				if(!$res[0]['create_finish_time']){
					$msign = 1;
				}
				$upost = array('create_finish_time'=> THIS_DATETIME);
				$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				if($post['plat_name'] == 'yujun'){
					$hash = md5($post['interface_key'].$post['pname'].$post['plat_host']);
					file_get_contents($post['interface_url'].'?user_account='.urlencode($post['pname']).'&serverurl='.urlencode($post['plat_host']).'&hash='.$hash.'&promotecode='.$post['promotecode']);
				}
				break;
			case 6://进入游戏界面
				if($post['first_load']){
					$upost = array('last_conn_result'=>1, 'first_entergame_time'=>THIS_DATETIME, 'last_entergame_time'=>THIS_DATETIME, 'entergame_num'=>1, 'first_login_ip'=>$post['ip'], 'last_login_ip'=>$post['ip']);
				}else{
					$upost = array('last_conn_result'=>1, 'last_entergame_time'=>THIS_DATETIME, 'entergame_num'=>$entergame_num+1, 'last_login_ip'=>$post['ip']);
				}
				$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				break;
			case 7://进入游戏后的活跃玩家，排除第一次进入游戏后无操作的玩家
				if($post['first_load']){
					$upost = array('active_user'=> THIS_DATETIME);
					$update = $this->load('cls_log_register','admintool')->update($upost,$where);
				}
				break;
			case 8://连接服务器失败的玩家
				if($post['first_load']){
					$upost = array('last_unconn_server_time'=>THIS_DATETIME, 'unconn_server_num'=>1);
				}else{
					$upost = array('last_unconn_server_time'=>THIS_DATETIME, 'unconn_server_num'=>$unconn_server_num+1);
				}
				$update = $this->load('cls_log_register','admintool')->update($upost,$where);			
				break;
		}
	}
	
	/**
	 * 根据角色名查询角色ID
	 *
	 * @param array $post
	 * @return array
	 */
	public function searchroleid($post){
		$role_name_str = '';
		if(!empty($post['role_name_list'])){
			$rolenamearr = explode(',',$post['role_name_list']);
			foreach ($rolenamearr as $val){
				$role_name_str .= "'{$val}',";
			}
			$role_name_str = rtrim($role_name_str,',');
		}else{
			$role_name_str = "'{$post['role_name']}'";
		}
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->setectinname($role_name_str);
		if(!empty($roleinfo)){
			$roleidarr = array();
			foreach ($roleinfo as $key => $val){
				$roleidarr[$key]['role_name'] = $val['role_name'];
				$roleidarr[$key]['role_id'] = $val['role_id'];
			}
			return $this->result(1,'','',$roleidarr);
		}
	}
	
	/**
	 * 查询武将装备，仙法，丹药信息
	 *
	 * @param array $post
	 * @return array
	 */
	public function roleofficerinfo($post){
		if(empty($post['role_id'])){
			return $this->result(0);
		}
		$role_officer_info = array();
		if($post['flag'] == 1){
		    $roleinfo = $this->load('cls_role_attr_detail','mmoarpg')->records(array('field'=>'role_id','val'=>$post['role_id']),'','','','','','role_name,level,sex,prof,camp,capability,guild_name,role_color');
		    if(!empty($roleinfo)){
		    	$role_officer_info = $roleinfo[0];
		    }
		    if($post['need_officer_index'] == 1){
		    	$officerinfo = $this->load('cls_officer','mmoarpg')->records(array('field'=>'role_id','val'=>$post['role_id']),'','','','','','officer_index,officer_id');
		    	if(!empty($officerinfo)){
		    		$role_officer_info['officer_index_list'] = $officerinfo;
		    	}
		    }
		    
		}
		
		if($post['flag'] == 2){
			$max_officer_list_num = 10;		// 最多10个武将
			$common_equipment_num = 8;		// 8个普通装备
			$xianfa_num = 8;				// 8个仙法位置

			$min_special_equipment = 80;	// 角色身上多了3件特殊装备，>= $min_special_equipment
			$max_special_equipment = 82;	// <= $max_special_equipment
			if ($post['officer_index'] < 0 || $post['officer_index'] >= $max_officer_list_num ||
			$post['equipment_index'] < 0 || $post['equipment_index'] >= $common_equipment_num){
				return $this->result(0);
			}
			$officerinfo = $this->load('cls_officer','mmoarpg')->records(array(array('field'=>'officer_index','val'=>$post['officer_index']),array('field'=>'role_id','val'=>$post['role_id'])),'','','','','','role_id,officer_index,officer_name,officer_id,officer_type,level,exp,max_exp,hp,max_hp,mp,max_mp,common_attack,common_defend,strategy_attack,strategy_defend,magic_attack,magic_defend');
			if(!empty($officerinfo)){
				$role_officer_info = $officerinfo[0];
			}
			//获得武将装备信息
			$min_equipment_array_index = $post['officer_index'] * $common_equipment_num;					// >= $min_equipment_array_index
	        $max_equipment_array_index = $min_equipment_array_index + $common_equipment_num - 1;	// <= $max_equipment_array_index
	        $equipinfo = $this->load('cls_equipment','mmoarpg')->records(array(array('field'=>'role_id','val'=>$post['role_id']),array('field'=>'array_index','val'=>$min_equipment_array_index,'type'=>'>='),array('field'=>'array_index','val'=>$max_equipment_array_index,'type'=>'<=')));
	        if(!empty($equipinfo)){
	        	$role_officer_info['officer_equipment_info'] = $equipinfo;
	        	if($post['officer_index'] == 0){//如果是角色，则多查询3件特殊装备
	        		$equipspecialinfo = $this->load('cls_equipment','mmoarpg')->records(array(array('field'=>'role_id','val'=>$post['role_id']),array('field'=>'array_index','val'=>$min_special_equipment,'type'=>'>='),array('field'=>'array_index','val'=>$max_special_equipment,'type'=>'<=')));
	        		if(!empty($equipspecialinfo)){
	        			foreach ($equipspecialinfo as $val){
	        			  $role_officer_info['officer_equipment_info'][] = $val;
	        			}
	        		}
	        	}
	        }
	        //获得武将仙法信息
	        $min_xianfa_array_index = $post['officer_index'] * $xianfa_num;						// >= $min_xianfa_array_index
	        $max_xianfa_array_index = $min_xianfa_array_index + $xianfa_num - 1;		// <= $max_xianfa_array_index
	        $xianfainfo = $this->load('cls_xianfa','mmoarpg')->records(array(array('field'=>'role_id','val'=>$post['role_id']),array('field'=>'xianfa_index','val'=>$min_xianfa_array_index,'type'=>'>='),array('field'=>'xianfa_index','val'=>$max_xianfa_array_index,'type'=>'<=')),'','','','','','xianfa_index,xianfa_id,xianfa_level');
	        if(!empty($xianfainfo)){
	        	$role_officer_info['officer_xianfa_info'] = $xianfainfo;
	        }
	        //获得武将丹药信息
	        $danyaoinfo = $this->load('cls_officer','mmoarpg')->records(array(array('field'=>'officer_index','val'=>$post['officer_index']),array('field'=>'role_id','val'=>$post['role_id'])),'','','','','','danyao_eaten_num_1,danyao_eaten_num_2,danyao_eaten_num_3,danyao_eaten_num_4,danyao_eaten_num_5,danyao_eaten_num_6,danyao_eaten_num_7,danyao_eaten_num_8,danyao_eaten_num_9,danyao_eaten_num_10,danyao_eaten_num_11,danyao_eaten_num_12');
	        if(!empty($danyaoinfo)){
	        	$role_officer_info['officer_danyao_info'] = $danyaoinfo[0];
	        }
	        
		}
		return $this->result(1,'','',$role_officer_info);
	}
	
	/**
	 * 元宝消费类型
	 *
	 * @param array $post
	 * @return array
	 */
	public function costgold($post){
		if(empty($post['date']) || empty($post['time']) || empty($post['ticket']) || strlen($post['date']) != 10){
			return $this->result(0,$post['result']['param_error']);
		}
		
		$ticket = md5($post['date'].$post['time'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$start_time = strtotime($post['date']);
		$end_time = $start_time + 86400;
		$goldinfo = $this->load('cls_log_money_gold','admintool')->getlistbyusefor($start_time,$end_time);
		if(!empty($goldinfo)){
			return $this->result(1,'','',$goldinfo);
		}else{
			return $this->result(0,$post['result']['data_error']);
		}
	}
	
	/**
	 * 角色创建个数
	 *
	 * @param array $post
	 * @return array
	 */
	public function rolenum($post){
		if(empty($post['date']) || empty($post['time']) || empty($post['ticket']) || strlen($post['date']) != 10){
			return $this->result(0,$post['result']['param_error']);
		}
		
		$ticket = md5($post['date'].$post['time'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$start_time = strtotime($post['date']);
		$end_time = $start_time + 86400;
		$where = array(
		    array('field'=>'create_time','val'=>$start_time,'type'=>'>='),
		    array('field'=>'create_time','val'=>$end_time,'type'=>'<'),
		    array('field'=>'role_name','val'=>'\0','type'=>'!='),
		);
		$result = $this->load('cls_role','mmoarpg')->records($where,'','','','','','count(*) as num');
		if(!empty($result)){
			return $this->result(1,'','',$result[0]['num']);
		}else{
			return $this->result(0,$post['result']['data_error']);
		}
	}
	
	/**
	 * 返回指定时间当天创建的角色名，创建时间，平台名（不包括服ID）
	 *
	 * @param array $post
	 * @return array
	 */
	public function rolecreatelist($post){
		if(empty($post['date']) || empty($post['time']) || empty($post['ticket']) || strlen($post['date']) != 10){
			return $this->result(0,$post['result']['param_error']);
		}
		
		$ticket = md5($post['date'].$post['time'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$start_time = strtotime($post['date']);
		$end_time = $start_time + 86400;
		$where = array(
		    array('field'=>'create_time','val'=>$start_time,'type'=>'>='),
		    array('field'=>'create_time','val'=>$end_time,'type'=>'<'),
		    array('field'=>'role_name','val'=>'\0','type'=>'!='),
		);
		$result = $this->load('cls_role','mmoarpg')->records($where);
		if(!empty($result)){
			$roleid = array();
			$roleinfo = array();
			foreach ($result as $val){
				$roleid[] = $val['role_id'];
				$roleinfo[$val['role_id']] = $val['create_time'];
			}
			$roleids = join(',',$roleid);
			$result = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'role_id','val'=>$roleids,'type'=>'in'));
			if(!empty($result)){
				$rolelist = array();
				foreach ($result as $key => $val){
					$rolelist[$key]['create_time'] = date('Y-m-d H:i:s',$roleinfo[$val['role_id']]);
					$rolelist[$key]['role_name'] = $val['role_name'];
					$rolelist[$key]['plat_user_name'] = substr($val['plat_user_name'],0,strrpos($val['plat_user_name'],'_'));
				}
				return $this->result(1,'','',$rolelist);
			}
			return $this->result(0,$post['result']['data_error']);
		}else{
			return $this->result(0,$post['result']['data_error']);
		}
	}
	
	/**
	 * 注册流失率统计
	 *
	 * @param array $post
	 * @return array
	 */
    public function reglost($post){
    	if(empty($post['date']) || empty($post['time']) || empty($post['ticket']) || strlen($post['date']) != 10){
			return $this->result(0,$post['result']['param_error']);
		}
		
		$ticket = md5($post['date'].$post['time'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		
		$start_time = strtotime($post['date']);
		$end_time = $start_time + 86400;

		$where = array();
		$where[] = array('field'=>'start_time','val'=>$start_time,'type'=>'>=');
		$where[] = array('field'=>'end_time','val'=>$end_time,'type'=>'<');
		$where[] = array('field'=>'type','val'=>86400);
		$orderby = array('field'=>'start_time','type'=>'desc');
		$result = $this->load('cls_log_register_result')->records($where,$orderby);
		if(!empty($result)){
			$list = array();
			$list = $result;
			foreach ($list as $key => $value){
				$list[$key]['loading_rate'] =  round(($value['loading_start_num'] / (empty($value['new_loading_num']) ? 1 : $value['new_loading_num'])) * 100, 2); //资源加载流失率
				$list[$key]['create_rate'] = round(($value['create_unfinish_num'] / (empty($value['create_num']) ? 1 : $value['create_num'])) * 100, 2); //创建角色流失率
			//进入场景流失率	
			$list[$key]['create_finish_rate'] = round((($value['create_num'] - $value['create_unfinish_num'] - $value['new_entergame_num']) / (empty($value['new_loading_count']) ? 1 : $value['new_loading_count'])) * 100, 2);
				
			//未到达注册页百分比
			$list[$key]['loading_rate_more'] = $data['loading_rate'];
			//未创建角色百分比
			$list[$key]['create_rate_more'] = round((($value['new_loading_count']- $value['create_num'] - $value['create_unfinish_num']) / (empty($value['new_loading_count']) ? 1 : $value['new_loading_count'])) * 100, 2);
			//未进入游戏百分比
			$list[$key]['create_finish_rate_more'] = round((($value['new_loading_count']-$value['new_entergame_num']) / (empty($value['new_loading_count']) ? 1 : $value['new_loading_count'])) * 100, 2);
			}
			return $this->result(1,'','',$list);
		}
    }
    
    
    /**
     * 玩家等级统计
     *
     * @param array $post
     * @return array
     */
    public function rolelevel($post){
    	if(empty($post['date']) || empty($post['time']) || empty($post['ticket']) || strlen($post['date']) != 10){
			return $this->result(0,$post['result']['param_error']);
		}
		
		$ticket = md5($post['date'].$post['time'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		
		$start_time = strtotime($post['date']);
		$end_time = $start_time + 86400;

		$where = array();
		$where[] = array('field'=>'time','val'=>$start_time,'type'=>'>=');
		$where[] = array('field'=>'time','val'=>$end_time,'type'=>'<');
		$result = $this->load('cls_log_role_level')->records($where);
		if(!empty($result)){
			$list['level'] = $result[0];
			$arr = explode(' ', $list['level']['level_num'] );
			array_shift( $arr ); //level_num等级人数分布
			if(count($arr)>0){
				foreach($arr as $v){
					$tem = explode(':', $v);
					$result[ $tem[0]+1 ] = $tem[1];
					if($tem[0] > $last){
						$last = $tem[0];
					}
				}
				for($i=1;$i<=$last;$i++){
					$info['level_'.$i] = ($result[$i+1]=="") ? 0 : $result[$i+1];
				}
				return $this->result(1,'','',$info);
			}
			return $this->result(0,$post['result']['data_error']);
		}
		return $this->result(0,$post['result']['data_error']);
    }
    
    /**
     * 获取题库内容接口
     * 
     * @param array $post
     * @return array
     */
	public function question($post){
		if(empty($post['id']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);
		}		
		$ticket = md5($post['id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$result = $this->load('cls_base_question')->getrecordbyid($post['id']);
		if(empty($result)){
			return $this->result(0,$post['result']['data_error']);
		}
		$result['answer'] = $result['opt1'];
		return $this->result(1,'','',$result);
	}
	
    /**
     * 获取元宵灯谜题库接口
     * 
     * @param array $post
     * @return array
     */
	public function lanternquestion($post){
		if(empty($post['id']) || empty($post['ticket'])){
			return $this->result(0,'-1');
		}		
		$ticket = md5($post['id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,'-2');
		}
		$result = $this->load('cls_lantern_question')->getrecordbyid($post['id']);
		if(empty($result)){
			return $this->result(0,'-3');
		}
		return $this->result(1,'','',$result);
	}	
	
	/**
	 * 获取玩家装备列表接口
	 * @param array $post
	 * @return array
	 */
	public function itemlist($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		if(empty($post['itemlist_index'])){
			$result = $this->load('cls_itemlist','mmoarpg')->getrecordbyroleid($post['role_id']);
			if(empty($result)){
				return $this->result(1,'','',$result);
			}			
			foreach($result as $k=>$v){
				$result[$k]['itemlist_index'] = $v['itemlist_index'] - 1000; 
			}
			return $this->result(1,'','',$result);			
		}else{
			$result = $this->load('cls_itemlist','mmoarpg')->getrecordbyitemlist_index($post['role_id'],$post['itemlist_index']);
			if(empty($result)){
				return $this->result(0,$post['result']['data_error']);
			}
			$result['itemlist_index'] = $result['itemlist_index'] - 1000; 
			return $this->result(1,'','',$result);			
		}

	}

	/**
	 * 获取玩家宠物信息列表接口
	 * @param array $post
	 * @return array
	 */
	public function getPet($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$result = $this->load('cls_pet','mmoarpg')->getRecordByPetIndex($post['role_id'],$post['index']);
		if(empty($result)){
			return $this->result(0,$post['result']['data_error']);
		}
		return $this->result(1,'','',$result);			
	}
	
	/**
	 * 获取玩家坐骑信息列表接口
	 * @param array $post
	 * @return array
	 */
	public function getMount($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$result = $this->load('cls_mount','mmoarpg')->getRecordByMountIndex($post['role_id'],$post['index']);
		if(empty($result)){
			return $this->result(0,$post['result']['data_error']);
		}
		return $this->result(1,'','',$result);			
	}	
	
	/**
	 * 获取玩家将魂信息列表接口
	 * @param array $post
	 * @return array
	 */
	public function getSoul($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['result']['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['result']['ticket_error']);
		}
		$result = $this->load('cls_soul','mmoarpg')->getRecordBySoulIndex($post['role_id'],$post['index']);
		if(empty($result)){
			return $this->result(0,$post['result']['data_error']);
		}
		return $this->result(1,'','',$result);			
	}

	/**
	 * 获取玩家最后登录时间列表接口
	 * @param array $post 玩家ID列表字符串，签名，加密密钥
	 * @return array 返回玩家ID和最后登录时间的数组 
	 */
	public function getLastLoginTime($post){
		if(empty($post['role_id_arr']) || empty($post['ticket'])){
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id_arr'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_login','mmoarpg')->getLastLoginTime($post['role_id_arr']);
		if(empty($result)){
			return $this->result(0,$post['data_error']);
		}
		return $this->result(1,'','',$result);			
	}
	
	/**
	 * 获取玩家好友信息接口
	 * @param array $post 玩家ID，玩家好友ID，加密密钥
	 * @return array 返回玩家好友信息及亲密度 
	 */
	public function getFriends($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_role_attr_detail','mmoarpg')->getFriends($post['role_id']);
		if(empty($result)){
			return $this->result(0,$post['data_error']);
		}
		return $this->result(1,'','',$result);			
	}

	/**
	 * 获取玩家好友信息接口
	 * @param array $post 玩家ID，玩家好友ID，加密密钥
	 * @return array 返回玩家好友信息及亲密度 
	 */
	public function getLover($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_role_attr_detail','mmoarpg')->getLover($post['role_id']);
		if(empty($result)){
			return $this->result(0,$post['data_error']);
		}
		return $this->result(1,'','',$result);			
	}

	/**
	 * 获取新手指导员信息接口
	 * @param array $post 玩家ID，加密密钥
	 * @return array 返回新手管理员列表
	 */
	public function getGuides($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_role_attr_detail','mmoarpg')->getGuides();
		if(empty($result)){
			return $this->result(0,$post['data_error']);
		}
		return $this->result(1,'','',$result);			
	}

	/**
	 * 判断玩家是否互为好友接口
	 * @param array $post 玩家ID，玩家好友ID列表，加密密钥
	 * @return array 返回未存在于数据库中的好友记录 
	 */
	public function judgeFriends($post){
		if(empty($post['role_id']) || empty($post['ticket']) || empty($post['friends_id'])){
			//参数错误			
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			//签名错误
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_friends','mmoarpg')->judgeFriends($post['role_id']);
		$friends = explode(',', $post['friends_id']);
		foreach($friends as $k=>$v){
			$data = explode('|', $v);
			$sex[$data[0]] = $data[1];
			$roles[] = $data[0];
		}
		if(empty($result)){
			//数据库没有好友，则返回所有好友
			$rt = array('friends'=>$roles,'sex'=>array());
			return $this->result(1,'','',$rt);
		}else{
			foreach($result as $r){
				$rtes[] = $r['role_id'];
			}
			//比对
			foreach($roles as $f){
				if(!in_array($f, $rtes)){
					$R[] = $f;
				}
				//查询性别是否正确
				else{
					$where = array(array('field'=>'role_id','val'=>$f),array('field'=>'sex','val'=>$sex[$f]));
					if(!$res = $this->load('cls_role','mmoarpg')->records($where,'','','','',true)){
						$RS[] = $f;
					}
				}
			}
		}
		$rt = array('friends'=>$R,'sex'=>$RS);		
		return $this->result(1,'','',$rt);			
	}

	/**
	 * 判断玩家是否在好友黑名单中接口
	 * @param array $post 玩家ID，玩家好友ID，加密密钥
	 * @return array 返回未存在于数据库中的好友记录 
	 */
	public function judgeBlack($post){
		if(empty($post['role_id']) || empty($post['ticket']) || empty($post['friend_id'])){
			//参数错误
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			//签名错误
			return $this->result(0,$post['ticket_error']);
		}
		$result = $this->load('cls_blacklist','mmoarpg')->judgeBlack($post['role_id'],$post['friend_id']);
		if(empty($result)){
			//不在黑名单中，返回4
			return $this->result(1,'','',4);
		}else{
			//存在黑名单中，返回5
			return $this->result(1,'','',5);
		}							
	}

	/**
	 * 获取玩家等级列表接口
	 * @param array $post 玩家ID列表字符串，签名，加密密钥
	 * @return array 返回玩家ID和最后登录时间的数组 
	 */
	public function getLevel($post){
		if(empty($post['role_id_arr']) || empty($post['ticket'])){
			return $this->result(0,$post['param_error']);
		}
		$ticket = md5($post['role_id_arr'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,$post['ticket_error']);
		}
		$where = array('field'=>'role_id','val'=>$post['role_id_arr'],'type'=>'in');
		$field = array('role_id','level','vip_type','vip_data');
		$result = $this->load('cls_role_attr_detail','mmoarpg')->records($where,'','','','','',$field);
		if(empty($result)){
			return $this->result(0,$post['data_error']);
		}
		return $this->result(1,'','',$result);			
	}

	/**
	 * 获取感恩礼包接口
	 */
	public function getReturn($post){
		if(empty($post['role_id']) || empty($post['plat_user_name']) || empty($post['ticket'])){
			return $this->result(0,'-1');  //参数错误
		}
		$ticket = md5($post['role_id'].$post['plat_user_name'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,'-2');  //签名错误
		}
		//检查该玩家是否符合条件
		$where[] = array('field'=>'plat_user_name','val'=>$post['plat_user_name']);
		$where[] = array('field'=>'timestamp','val'=>0);
		$result = $this->load('cls_count_return')->count($where);
		if(empty($result)){
			return $this->result(0,'-3');   //玩家不存在，或者已经领取了礼包
		}

		//通过验证，给玩家发放礼包
		$subject = '感恩礼包';
		$content = '亲爱的玩家，恭喜您在上次删档测试中达到45级，现赠送感恩礼包一份！感谢您在封测期间给予我们大力支持，祝您游戏愉快！';
		$p = array(
				'uid'=>$post['role_id'], 								    //角色ID
				'recv_time'=>THIS_DATETIME, 								//收到邮件的时间
				'kind'=>2, 													//邮件类型，2为系统邮件
				'item_id1'=>28245, 										//物品ID
				'item_num1'=>1,	 									        //物品数量
				'subject'=>$subject, 										//邮件主题
				'content'=>$content, 										//邮件内容
	    );
		$pinsert = $this->load('cls_systemmail','mmoarpg')->insert($p);//赠送物品邮件
		$command = array(
						       'creator' => 'background',		// 后台
						       'createtime' => time(),			//
						       'type' => 2,					// loginserver
						       'cmd' => "newnotice 2 {$post['role_id']}",
			
						   );
		cls_entry::load('command')->add($command);//插入命令行
		
		//更新数据库
		$update = $this->load('cls_count_return','admintool')->update(array('timestamp'=>time()),array('field'=>'plat_user_name','val'=>$post['plat_user_name']));
		return $this->result(1,'','',1);
	}
	
	/**
	 * 获取转盘礼包
	 */
	public function useturnplate($post){
		if(empty($post['card']) || empty($post['username']) || empty($post['role_id']) || empty($post['role_name']) || empty($post['time']) || empty($post['ticket'])){
			return $this->result(0,'0');//参数不齐全
		}
		
		//设置5分钟连接超时时间
		if(THIS_DATETIME - $post['time'] > 300){
			return $this->result(0,'1');//连接超时
		}
				
	}
	
	/**
	 * 获取客户端登陆日志
	 * 
	 */
	public function getLog($post){
		if(empty($post['plat_user_name']) || empty($post['log']) || empty($post['ticket'])){
			return $this->result(0,'-1');   //参数不全
		}
		$ticket = md5($post['plat_user_name'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,'-2');  //签名错误
		}
		$dir = PATH_ADMIN_LOG.'/client/';
		if(!is_dir($dir)){
			mkdir($dir,0777);
		}
		$file = $dir.$post['plat_user_name'].'.log';
		if($post['state'] == 1){			
			file_put_contents($file, $post['log']);
		}else{
			if(file_exists($file)){
				$f = filesize($file);
				if($f > 10240){
					return $this->result(0,'-3'); //文件大小超过10KB，无法继续追加
				}
			}
			file_put_contents($file, "\r\n".$post['log'],FILE_APPEND);
		}
		return $this->result(1,'','',1);		
	}

	/**
	 * 批量获取玩家信息
	 * 
	 */
	public function getRoleInfos($post){
		if(empty($post['uids']) || empty($post['ticket'])){
			return $this->result(0,'-1');   //参数不全
		}
		$ticket = md5($post['uids'].$post['md5_key']);
		if($post['ticket'] != $ticket){
			return $this->result(0,'-2');  //签名错误
		}
		$uids = explode(',', $post['uids']);
		foreach($uids as $u){
			$res = $this->load('cls_role','mmoarpg')->records(array('field'=>'role_id','val'=>$u),'','','','','','role_id as uid,role_name,sex,professional');
			if(!empty($res)){
				$r[] = $res[0];
			}
		}
		if(!empty($r)){
			return $this->result(1,'','',$r);
		}else{
			return $this->result(0,'-3');
		}		
	}
	
	/**
	 * 批量获取玩家名字接口
	 * 
	 */
	public function getRoleName($post){
		if(empty($post['role_id']) || empty($post['ticket'])){
			return $this->result(0,'-1');   //参数不全
		}
		$ticket = md5($post['role_id'].$post['md5_key']);		
		if($post['ticket'] != $ticket){
			return $this->result(0,'-2');  //签名错误
		}
		$res = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'role_id','val'=>$post['role_id'],'type'=>'IN'),'','','','','','role_id,role_name');
		if(!empty($res)){
			foreach($res as $r){
				$rt[] = array(
					'role_id'=>$r['role_id'],
					'role_name'=>$r['role_name'],
				);
			}
			return $this->result(1,'','',$rt);
		}else{
			return $this->result(0,'-3');  //没有角色名信息
		}		
	}	

}
?>