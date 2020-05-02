<?php
/**
 * 接口API
 * @author JustFantasy
 */
class api_app extends cls_control 
{
	/**
	 * 充值接口
	 * @param array $post
	 * sid 服务器ID  s1/S1/1
	 * paynum 订单号
	 * mode 充值模式
	 * user 平台账号
	 * money 充值金额
	 * gold 充值元宝
	 * time 充值时间
	 * ticket 验证签名
	 */
	public function pay($post){
		//请求失败日志
		$errorlog = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$post['user'].'_'.$post['sid']."\tip:".$post['ip']
				."\tpaynum:".$post['paynum']."\tmode:".$post['mode']."\tmoney:".$post['money']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s")."]"
				."\tticket:".$post['ticket']."\tresult:";
		if(!isset($post['sid']) || empty($post['paynum']) || empty($post['mode']) || empty($post['user']) || empty($post['money']) || empty($post['gold']) || empty($post['time']) || empty($post['ticket'])){
			//充值失败写入日志
			$errorlog .= "参数不齐全r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['error_param']);//参数不齐全
		}
		//加密串验证
		if($post['check_sign'] != $post['ticket']){
			//充值失败写入日志
			$errorlog .= "密钥错误\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['error_check']);//验证错误
		}
		
		if(!empty($post['limit_ip'])){
			if(empty($post['ip'])){
				//充值失败写入日志
				$errorlog .= "IP错误\r\n";
				$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
				return $this->result(0,$post['error_ip']);//IP错误			
			}else{
				if(!in_array($post['ip'], $post['limit_ip'])){
					//充值失败写入日志
					$errorlog .= "IP错误\r\n";
					$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
					return $this->result(0,$post['error_ip']);//IP错误						
				}
			}
		}
		
		$sid = str_replace(array('S','s'), '', $post['sid']);
		$plat_user_name = $post['user'].'_'.intval($sid);//平台名由用户名加服ID
		//查询是否存在该玩家信息
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','role_id,role_name');
		if(empty($roleinfo)){
			//充值失败写入日志
			$errorlog .= "用户不存在\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['error_user']);//用户不存在
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
			$errorlog .= "订单重复\r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['error_repeat']);
		}
		//查询元宝表
		$accountinfo = $this->load('cls_accountgold','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','gold,gold_history');
		$log = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$plat_user_name."\trole_id:".$role_id."\trole_name:".$role_name."\tip:".$post['ip']
				."\tpaynum:".$post['paynum']."\tmode:".$post['mode']."\tmoney:".$post['money']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s", $post['time'])."]"
				."\tticket:".$post['ticket']."\tresult:";
		if(empty($accountinfo)){//如果不存在充值记录，则新插入
			$paccount = array(
				'plat_user_name'=>$plat_user_name,
				'gold'=>$post['gold'],
				'gold_history'=>$post['gold'],
				'last_get_gold_time'=>0,
			);
			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$insert = $this->load('cls_accountgold','mmoarpg')->insert($paccount);
			if(!$insert){
				//充值失败写入日志，回滚之前的操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "充值失败\r\n";
				$this->error($log,'api.pay.'.date('Ymd'),0,'pay/');
				return $this->result(0,$post['error_time']); //充值失败,请求超时
			}
		}else{//否则，更新记录
			$paccount = array(
			   'gold'=>$accountinfo[0]['gold'] + intval($post['gold']),//服务器响应后会清为0，但是为了防止连续充值，需要用原来的值加上新的元宝
			   'gold_history'=>$accountinfo[0]['gold_history'] + intval($post['gold']),
			);
			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$update = $this->load('cls_accountgold','mmoarpg')->update($paccount,array('field'=>'plat_user_name','val'=>$plat_user_name));
			if(!$update){
				//充值失败写入日志，回滚之前的操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "充值失败\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.fail',0,'pay/');
				return $this->result(0,$post['error_time']); //充值失败,请求超时
			}
		}
			//充值成功，插入充值记录到后台充值订单表
			$pcharge = array(
								'server_id' => intval($sid),        //服ID
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
								'result'	=> 8,	//充值结果
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
				return $this->result(0,$post['error_repeat']);   //订单重复了
			}
			$pinsert = $this->load('cls_charge','admintool')->insert($pcharge);
			if(!$pinsert){
				$log .= "后台生成订单失败\tvalue:".var_export($pcharge,true)."\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
				//订单生成失败，回滚操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				return $this->result(0,$post['error_time']); //充值失败
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
			$log .= "充值成功\r\n";
			$this->error($log,'api.pay.'.date('Ymd').'.succ',0,'pay/');
		    return $this->result(1,'','',$post['success']);
	}
	
	//获取单个玩家信息接口,迅雷接口
	public function getPlayerInfo($post){
		if(!isset($post['server_id']) || empty($post['user']) || empty($post['time']) || empty($post['sign'])){
			return $this->result(0,$post['error_param']); //缺少参数
		}
		if($post['sign'] != $post['check_sign']){
			return $this->result(0,$post['error_check']);  //参数验证失败
		}
		$sid = str_replace(array('S','s'), '', $post['server_id']);
		$plat = $post['user'].'_'.$sid;
		$where = array('field'=>'plat_user_name','val'=>$plat);
		
		$join = array(
			'table'=>'role_name_map AS b','type'=>'AS a LEFT','left'=>'a.role_id','right'=>'b.role_id',
		);
		//查询字段，待增加
		$field = array('b.plat_user_name','a.role_name','a.level','a.sex','a.prof','a.gold','a.gold_bind','a.coin_bind','a.guild_name');
		//验证通过，通过平台名获取玩家的信息
		$res = $this->load('cls_role_attr_detail','mmoarpg')->records($where,'',$join,'','','',$field);
		if(!empty($res)){
			foreach($res as $r){
				$qid = explode('_', $r['plat_user_name']);
				unset($qid[count($qid)-1]);
				$user = join('_',$qid);
				$R= array(
					'user' => $user,					
					'nickname'  => $r['role_name'],					
					'sex'=> $post['sex'][$r['sex']],
					'career'=>$post['job'][$r['prof']],
					'level' => $r['level'],
					'gold'=>$r['gold'],
					'copper'=>$r['coin_bind'],
					'club_name'=>$r['guild_name'],
				);
			}
			$post['success']['data'] = $R;
			return $this->result(1,'','',$post['success']);
		}else{
			return $this->result(0,$post['error_user']); //获取玩家数据失败
		}
	}
	
	//获取当天在线时长
	public function getTodayOnlineTime($post){
		if(!isset($post['server_id']) || empty($post['user']) || empty($post['time']) || empty($post['sign'])){
			return $this->result(0,$post['error_param']); //缺少参数
		}
		if($post['sign'] != $post['check_sign']){
			return $this->result(0,$post['error_check']);  //参数验证失败
		}
		$sid = str_replace(array('S','s'), '', $post['server_id']);
		$plat = $post['user'].'_'.$sid;
		$where = array(array('field'=>'plat_user_name','val'=>$plat),array('field'=>'role_id_1','val'=>0,'type'=>'>'));
		
		$join = array(
			'table'=>'login AS b','type'=>'AS a LEFT','left'=>'a.role_id','right'=>'b.role_id_1',
		);
		//查询字段，待增加
		$field = array('b.plat_user_name','b.lastlogintime','a.today_online_time');
		//验证通过，通过平台名获取玩家的信息
		$res = $this->load('cls_role_attr_detail','mmoarpg')->records($where,'',$join,'','','',$field);
		if(!empty($res)){
			foreach($res as $r){
				$qid = explode('_', $r['plat_user_name']);
				unset($qid[count($qid)-1]);
				$user = join('_',$qid);
				if($r['lastlogintime'] > strtotime(date('Y-m-d 00:00:00'))){
					$onlinetime = $r['today_online_time'];
				}else{
					$onlinetime = 0;
				}
				$R= array(
					'user' => $user,					
					'onlineTime'  => $onlinetime,					
				);
			}
			$post['success']['data'] = $R;
			return $this->result(1,'','',$post['success']);
		}else{
			return $this->result(0,$post['error_user']); //获取玩家数据失败
		}
	}
}