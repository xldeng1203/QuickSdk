<?php

/**
 * QuickSDK游戏同步加解密算法描述
 * @copyright quicksdk 2015
 * @author quicksdk 
 * @version quicksdk v 0.0.1 2014/9/2
 */

class quickAsy{

	 //处理充值
	 public function OnQuickPay($post) {
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
	 }
		
	 //处理充值
	// public function OnQuickPay(res, post_data, get_data) {
	 	//uri转码
	// 	post_data.nt_data = decodeURIComponent(post_data.nt_data)
	// 	post_data.sign = decodeURIComponent(post_data.sign)

	// 	//先验证
	// 	if (pb.md5(post_data.nt_data + post_data.sign + quick_conf.Md5_Key) != post_data.md5Sign) {
	// 		//验证失败
	// 		res.OnSend('check sign faield')
	// 		pb.err(`quick pay check sign faield!`)
	// 		return
	// 	}

	// 	//解密
	// 	let xml_data = qucikDecode(post_data.nt_data, quick_conf.Callback_Key)
	// 	if (xml_data.length <= 0) {
	// 		res.OnSend('decode xml data faield')
	// 		pb.err(`quick decode xml data faield!`)
	// 		return
	// 	}

	// 	xml2js.parseString(xml_data, {
	// 		explicitArray: false,
	// 		ignoreAttrs: true
	// 	}, (err, result) => {
	// 		if (err) {
	// 			res.OnSend(`xml can't convert to json => ${err.message}`)
	// 			pb.err(`quick pay xml can't convert to json => ${err.message}`)
	// 		} else {
	// 			//转换成功,处理订单
	// 			let message = result.quicksdk_message.message
	// 			let game_data = message.extras_params.split('|')
	// 			if (game_data.length < 2) {
	// 				res.OnSend(`extras_params error [${message.extras_params}]`)
	// 				pb.err(`quick pay extras_params error [${message.extras_params}]`)
	// 			} else {
	// 				http_msg.PayMoney({
	// 					channelid: message.channel, //渠道ID
	// 					order: message.order_no, //订单号
	// 					account: message.channel_uid, //账号
	// 					amount: message.amount, //订单金额
	// 					idx: game_data[0], //充值角色所在服务器
	// 					actorid: game_data[1], //角色ID
	// 					time: message.pay_time, //平台处理时间
	// 				}, (msg) => {
	// 					if (msg == 'SUCCESS')
	// 						pb.command('充值成功')
	// 					else
	// 						pb.err(`充值失败 => ${msg}`)
	// 					//返回结果
	// 					res.OnSend(msg)
	// 				})
	// 			}
	// 		}
	// 	})
	// }


	/**
	 * 解密方法
	 * $strEncode 密文
	 * $keys 解密密钥 为游戏接入时分配的 callback_key
	 */
	public function decode($strEncode, $keys) {
		if(empty($strEncode)){
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
			return self::toStr($data);
		} else {
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