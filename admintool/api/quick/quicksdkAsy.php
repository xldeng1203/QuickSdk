<?php

/**
 * QuickSDK��Ϸͬ���ӽ����㷨����
 * @copyright quicksdk 2015
 * @author quicksdk 
 * @version quicksdk v 0.0.1 2014/9/2
 */

class quickAsy{

	 //�����ֵ
	 public function OnQuickPay($post) {
		//����ʧ����־
		$errorlog = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$post['user'].'_'.$post['sid']."\tip:".$post['ip']
				."\tpaynum:".$post['paynum']."\tmode:".$post['mode']."\tmoney:".$post['money']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s")."]"
				."\tticket:".$post['ticket']."\tresult:";
		if(!isset($post['sid']) || empty($post['paynum']) || empty($post['mode']) || empty($post['user']) || empty($post['money']) || empty($post['gold']) || empty($post['time']) || empty($post['ticket'])){
			//��ֵʧ��д����־
			$errorlog .= "��������ȫr\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,$post['error_param']);//��������ȫ
		}
	 }
		
	 //�����ֵ
	// public function OnQuickPay(res, post_data, get_data) {
	 	//uriת��
	// 	post_data.nt_data = decodeURIComponent(post_data.nt_data)
	// 	post_data.sign = decodeURIComponent(post_data.sign)

	// 	//����֤
	// 	if (pb.md5(post_data.nt_data + post_data.sign + quick_conf.Md5_Key) != post_data.md5Sign) {
	// 		//��֤ʧ��
	// 		res.OnSend('check sign faield')
	// 		pb.err(`quick pay check sign faield!`)
	// 		return
	// 	}

	// 	//����
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
	// 			//ת���ɹ�,������
	// 			let message = result.quicksdk_message.message
	// 			let game_data = message.extras_params.split('|')
	// 			if (game_data.length < 2) {
	// 				res.OnSend(`extras_params error [${message.extras_params}]`)
	// 				pb.err(`quick pay extras_params error [${message.extras_params}]`)
	// 			} else {
	// 				http_msg.PayMoney({
	// 					channelid: message.channel, //����ID
	// 					order: message.order_no, //������
	// 					account: message.channel_uid, //�˺�
	// 					amount: message.amount, //�������
	// 					idx: game_data[0], //��ֵ��ɫ���ڷ�����
	// 					actorid: game_data[1], //��ɫID
	// 					time: message.pay_time, //ƽ̨����ʱ��
	// 				}, (msg) => {
	// 					if (msg == 'SUCCESS')
	// 						pb.command('��ֵ�ɹ�')
	// 					else
	// 						pb.err(`��ֵʧ�� => ${msg}`)
	// 					//���ؽ��
	// 					res.OnSend(msg)
	// 				})
	// 			}
	// 		}
	// 	})
	// }


	/**
	 * ���ܷ���
	 * $strEncode ����
	 * $keys ������Կ Ϊ��Ϸ����ʱ����� callback_key
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
	 * ������Ϸͬ��ǩ��
	 */
	public static function getSign($params,$callbackkey){

		return md5($params['nt_data'].$params['sign'].$callbackkey);
	}
	
	/**
	 * MD5ǩ���滻
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
	 * ת���ַ�����
	 */
	private static function getBytes($string) {  
        $bytes = array();  
        for($i = 0; $i < strlen($string); $i++){  
             $bytes[] = ord($string[$i]);  
        }  
        return $bytes;  
    }  
    
    /**
     * ת���ַ���
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