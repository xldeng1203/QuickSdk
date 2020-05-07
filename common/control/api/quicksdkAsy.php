<?php

/**
 * QuickSDK游戏同步加解密算法描述
 * @copyright quicksdk 2020
 * @author neil deng 
 * @version quicksdk v 0.0.1 2020/5/2
 */

class quicksdkAsy extends cls_control 
{
	public function  test() {
		echo "public function test func  start <br>";
	}

	 //处理充值
	 public function OnQuickPay($post) {
		//$this->error("测试日志");
		echo "--------------quicksdkAsy.php  OnQuickPay  begin-----------------------------------------------<br>";
		//$this->log("测试日志", 'common/cls_control');
		//$this->log($err->getMessage(),'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		//请求失败日志

		echo "post[sign] = <br>";
		echo $post["sign"];
		echo "<br>";
		echo "post[md5Sign] = <br>";
		echo $post["md5Sign"];
		echo "<br>";
		echo "post[callback_key] = <br>";
		echo $post["callback_key"];
		echo "<br>";
		echo "post[nt_data] = <br>";
		echo $post["nt_data"];
		echo "<br>";

		//MD5校验
		echo "--------------------------------------sign------------------------------------------<br>";
		$decodeMD5 = self::getSign($post, $post["callback_key"]);

		echo "after demd5code=  $decodeMD5 <br>";

		if($decodeMD5 != $post["md5Sign"])
		{
			echo "decodeMD5 check is error <br>";
			return $this->result(0,$post['error_sign']); 
		}

		//解密
		echo "--------------------------------------decode------------------------------------------<br>";
		$decode_nt_data = self::decode($post["nt_data"], $post["callback_key"]);
		echo "after decode<br>";
		echo $decode_nt_data;
		echo "<br>";

		//$order_no = $decode_nt_data['order_no'];

		self::getExchange();
	 }
	
	
	//兑换元宝接口
	public function getExchange($post){
		echo "public function getExchange  <br>";
		//  //请求失败日志
		//  $errorlog = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$post['qid'].'_'.$post['server_id']
		//  		."\tpaynum:".$post['order_id']."\tmoney:".$post['order_amount']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s",$post['time'])."]";
		//  if(empty($post['qid']) || empty($post['server_id']) || empty($post['order_id']) || empty($post['order_amount']) || empty($post['sign'])){
		// 	//充值失败写入日志
		//  	$errorlog .= "\t失败原因：参数不齐全 \r\n";
		//  	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		//  	return $this->result(0,'','','0');//参数不齐全
		//  }
		// //加密串验证
		// //$sign=md5($qid.$order_amount.$order_id.$server_id.$key);
		// $my_sign = md5($post['qid'].$post['order_amount'].$post['order_id'].$post['server_id'].$post['key']);
		// if($my_sign != $post['sign']){
		// 	//充值失败写入日志
		// 	$errorlog .= "\t失败原因：密钥错误 \r\n";
		// 	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		// 	return $this->result(0,'','','0');//密钥错误
		// }


		$plat_user_name = "dev_212_1";
		//$plat_user_name = "aaa";
		$post['gold'] = 200;
		//临时赋值，正常应该是$decode_nt_data['order_no']
		$order_no = 11112;

		//查询是否存在该玩家信息
		//$plat_user_name = $post['qid'].'_'.intval($post['sid']);//平台名由用户名加服ID
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','role_id,role_name');
		if(empty($roleinfo)){
			echo "public function getExchange cls_role_name_map  is not  find player plat_user_name ".$plat_user_name."<br>";
			//充值失败写入日志
			$errorlog .= "\t失败原因：用户不存在 \r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,'','','0');//用户不存在
		}

		$role_id = $roleinfo[0]['role_id'];
		$role_name = $roleinfo[0]['role_name'];
		echo " cls_role_name_map  role_id: ".$role_id."<br>";
		echo " cls_role_name_map  role_name: ".$role_name."<br>";

		//获取玩家的充值等级
		$lvRes = $this->load('cls_role','mmoarpg')->records(array('field'=>'role_id','val'=>$role_id),'','','','','','level');
		if(!empty($lvRes)){
			$lv = $lvRes[0]['level'];
		}else{
			$lv = 1;  //无法查询到玩家的等级，此种情况应该不存在
		}

		echo " gggggggggggggggg cls_role  role_level: ".$lv."<br>";

		//检查订单的唯一性
		$totalcharge = $this->load('cls_charge','mmoarpg')->records(array('field'=>'paynum','val'=>$order_no),'','','','',true);
		echo "ccc totalcharge: ".$totalcharge."<br>";
		if($totalcharge){
			echo "######public function getExchange cls_charge is exist order_no: ".$order_no."<br>";
			echo "111c totalcharge: ".$totalcharge."<br>";
			//充值失败写入日志
			$errorlog .= "\t失败原因：订单重复 \r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,'','','2');   //订单重复了
		}

		echo " public function getExchange cls_accountgold   <br>";
		//查询元宝表
		$accountinfo = $this->load('cls_accountgold','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','gold,gold_history');
		$log = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$plat_user_name."\trole_id:".$role_id."\trole_name:".$role_name."\tpaynum:".$post['order_id']."\tmoney:".$post['order_amount']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s",$post['time'])."]";
		//echo "public function getExchange  $accountinfo  <br>"
		if(empty($accountinfo)){//如果不存在充值记录，则新插入
			echo "public function getExchange accountinfo  is NULL <br>";
			$paccount = array(
				'plat_user_name'=>$plat_user_name,
				'gold'=>$post['gold'],
				'gold_history'=>$post['gold'],
				'last_get_gold_time'=>0,
				'role_id'=>$role_id,
			);
			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$insert = $this->load('cls_accountgold','mmoarpg')->insert($paccount);
			if(!$insert){
				//充值失败写入日志，回滚操作
				echo "充值失败写入日志，回滚操作";
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "\t失败原因：充值失败（新插入数据库表[accountgold]失败） \r\n";
				$this->error($log,'api.pay.'.date('Ymd'),0,'pay/');
				return $this->result(0,'','','0'); //充值失败
			}
			echo "insert success insert =  $insert<br>";
		}else{//否则，更新记录
			echo "public function getExchange accountinfo  is have <br>";
			$paccount = array(
			   'gold'=>$accountinfo[0]['gold'] + intval($post['gold']),//服务器响应后会清为0，但是为了防止连续充值，需要用原来的值加上新的元宝
			   'gold_history'=>$accountinfo[0]['gold_history'] + intval($post['gold']),
			);
			echo "accountinfo[0][gold] =  ".$accountinfo[0]['gold']."<br>";
			echo "post[gold] =  ".$post['gold']."<br>";
			echo "paccount[gold] =  ".$paccount['gold']."<br>";

			//开启事务
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$update = $this->load('cls_accountgold','mmoarpg')->update($paccount,array('field'=>'plat_user_name','val'=>$plat_user_name));
			if(!$update){
				//充值失败写入日志，回滚操作
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "\t失败原因：充值失败（更新数据库表[accountgold]失败）\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.fail',0,'pay/');
				return $this->result(0,'','','0'); //充值失败
			}
		}
		$this->load('cls_accountgold','mmoarpg')->commit();

		$pcharge = array(
			'server_id' => 11111,        //服ID
			'paynum'	=> $order_no, 	//订单号							
			//  'mode'		=> 1, 		//充值方式
			//  'user'		=> 123456, 	    //充值平台帐号
			// 'role_id'	=> $role_id, 			//角色ID
			// 'role_name' => $role_name, 			//角色名称
			// 'money'		=> 999, 		//充值金额
			// 'gold'		=> intval($post['gold']),		//充值元宝
			// 'time'		=> THIS_DATETIME, 		//充值时间
			// 'ticket'	=> asd, 	//充值验证串
			// 'ip'		=> 123.435.234.123, 		//充值IP
			// 'result'	=> 8,	//充值结果,成功为8
			// 'lv'        => $lv,  //充值角色等级
		);

		//查看订单是否存在，若是不存在则执行插入操作，否则回滚之前的操作
		echo "public function getExchange cls_charge  check <br>";
		$totalcharge = $this->load('cls_charge','mmoarpg')->records(array('field'=>'paynum','val'=>$order_no),'','','','',true);
		echo "aaa totalcharge: ".$totalcharge."<br>";
		if($totalcharge){
		 	echo "222  public function getExchange cls_charge  is exist <br>";
		 	//回滚操作
		 	$this->load('cls_accountgold','mmoarpg')->rollback();
		 	//充值失败写入日志
		 	$errorlog .= "\t失败原因：订单重复 \r\n";
		 	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		 	return $this->result(0,'','','2');   //订单重复了
		}
		echo "public function getExchange cls_charge  insert  <br>";
		$pinsert = 0;
		$pinsert = $this->load('cls_charge','mmoarpg')->insert($pcharge);
		echo "bbb pinsert: ".$pinsert."<br>";
		if(!$pinsert){
		 	echo "public function getExchange cls_charge  insert  failed !!!! <br>";
		 	$log .= "\t失败原因：后台生成订单失败\tvalue:".var_export($pcharge,true)."\r\n";
		 	$this->error($log,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		 	//订单生成失败，回滚操作
		 	$this->load('cls_accountgold','mmoarpg')->rollback();
		 	return $this->result(0,'','','0'); //充值失败
		}
		//插入命令，优化充值后到账时间
		$pcommand = array(
			'creator' => 'background',		// 后台
			'createtime' => THIS_DATETIME,
			'type'	=> 2,					
			'cmd' => "newnotice 3 {$role_id}",
		);
		echo "public function getExchange cls_command  insert  <br>";
		$cinsert = $this->load('cls_command','mmoarpg')->insert($pcommand);
		//订单生成成功，执行事务
		$this->load('cls_accountgold','mmoarpg')->commit();
		$log .= "\t充值成功 \r\n";
		$this->error($log,'api.pay.'.date('Ymd').'.succ',0,'pay/');
		return $this->result(1,'','','1');	
	}


	/**
	 * 解密方法
	 * $strEncode 密文
	 * $keys 解密密钥 为游戏接入时分配的 callback_key
	 */
	public function decode($strEncode, $keys) {
		echo "public function decode  start <br>";
		if(empty($strEncode)){
			echo "public function decode empty strEncode <br>";
			return $strEncode;
		}
		preg_match_all('(\d+)', $strEncode, $list);
		$list = $list[0];
		if (count($list) > 0) {
			$keys = self::getBytes($keys);
			for ($i = 0; $i < count($list); $i++) {
				$keyVar = $keys[$i % count($keys)];
				$data[$i] =  $list[$i] - (0xff & $keyVar);
			}
			echo "public function decode 1111 <br>";
			return self::toStr($data);
		} else {
			echo "public function decode 2222 <br>";
			return $strEncode;
		}
	}
	
	/**
	 * 计算游戏同步签名
	 */
	public static function getSign($params,$callbackkey){

		return md5($params['nt_data'].$params['sign'].$callbackkey);
	}
	
	/**
	 * MD5签名替换
	 */
	static private function replaceMD5($md5){
	
		strtolower($md5);
		$bytes = self::getBytes($md5);

		$len = count($bytes);
		
	 	if ($len >= 23){
			$change = $bytes[1];
           	$bytes[1] = $bytes[13];
            $bytes[13] = $change;

            $change2 = $bytes[5];
            $bytes[5] = $bytes[17];
            $bytes[17] = $change2;

            $change3 = $bytes[7];
            $bytes[7] = $bytes[23];
            $bytes[23] = $change3;
       }else{
			return $md5;
       }
       
       return self::toStr($bytes);
	}
	
	/**
	 * 转成字符数据
	 */
	private static function getBytes($string) {  
        $bytes = array();  
        for($i = 0; $i < strlen($string); $i++){  
             $bytes[] = ord($string[$i]);  
        }  
        return $bytes;  
    }  
    
    /**
     * 转化字符串
     */
    private static function toStr($bytes) {  
        $str = '';  
        foreach($bytes as $ch) {  
            $str .= chr($ch);  
        }  
   		return $str;  
    }
}

?>