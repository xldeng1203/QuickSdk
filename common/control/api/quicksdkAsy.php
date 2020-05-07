<?php

/**
 * QuickSDK��Ϸͬ���ӽ����㷨����
 * @copyright quicksdk 2020
 * @author neil deng 
 * @version quicksdk v 0.0.1 2020/5/2
 */

class quicksdkAsy extends cls_control 
{
	public function  test() {
		echo "public function test func  start <br>";
	}

	 //�����ֵ
	 public function OnQuickPay($post) {
		//$this->error("������־");
		echo "--------------quicksdkAsy.php  OnQuickPay  begin-----------------------------------------------<br>";
		//$this->log("������־", 'common/cls_control');
		//$this->log($err->getMessage(),'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		//����ʧ����־

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

		//MD5У��
		echo "--------------------------------------sign------------------------------------------<br>";
		$decodeMD5 = self::getSign($post, $post["callback_key"]);

		echo "after demd5code=  $decodeMD5 <br>";

		if($decodeMD5 != $post["md5Sign"])
		{
			echo "decodeMD5 check is error <br>";
			return $this->result(0,$post['error_sign']); 
		}

		//����
		echo "--------------------------------------decode------------------------------------------<br>";
		$decode_nt_data = self::decode($post["nt_data"], $post["callback_key"]);
		echo "after decode<br>";
		echo $decode_nt_data;
		echo "<br>";

		//$order_no = $decode_nt_data['order_no'];

		self::getExchange();
	 }
	
	
	//�һ�Ԫ���ӿ�
	public function getExchange($post){
		echo "public function getExchange  <br>";
		//  //����ʧ����־
		//  $errorlog = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$post['qid'].'_'.$post['server_id']
		//  		."\tpaynum:".$post['order_id']."\tmoney:".$post['order_amount']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s",$post['time'])."]";
		//  if(empty($post['qid']) || empty($post['server_id']) || empty($post['order_id']) || empty($post['order_amount']) || empty($post['sign'])){
		// 	//��ֵʧ��д����־
		//  	$errorlog .= "\tʧ��ԭ�򣺲�������ȫ \r\n";
		//  	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		//  	return $this->result(0,'','','0');//��������ȫ
		//  }
		// //���ܴ���֤
		// //$sign=md5($qid.$order_amount.$order_id.$server_id.$key);
		// $my_sign = md5($post['qid'].$post['order_amount'].$post['order_id'].$post['server_id'].$post['key']);
		// if($my_sign != $post['sign']){
		// 	//��ֵʧ��д����־
		// 	$errorlog .= "\tʧ��ԭ����Կ���� \r\n";
		// 	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		// 	return $this->result(0,'','','0');//��Կ����
		// }


		$plat_user_name = "dev_212_1";
		//$plat_user_name = "aaa";
		$post['gold'] = 200;
		//��ʱ��ֵ������Ӧ����$decode_nt_data['order_no']
		$order_no = 11112;

		//��ѯ�Ƿ���ڸ������Ϣ
		//$plat_user_name = $post['qid'].'_'.intval($post['sid']);//ƽ̨�����û����ӷ�ID
		$roleinfo = $this->load('cls_role_name_map','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','role_id,role_name');
		if(empty($roleinfo)){
			echo "public function getExchange cls_role_name_map  is not  find player plat_user_name ".$plat_user_name."<br>";
			//��ֵʧ��д����־
			$errorlog .= "\tʧ��ԭ���û������� \r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,'','','0');//�û�������
		}

		$role_id = $roleinfo[0]['role_id'];
		$role_name = $roleinfo[0]['role_name'];
		echo " cls_role_name_map  role_id: ".$role_id."<br>";
		echo " cls_role_name_map  role_name: ".$role_name."<br>";

		//��ȡ��ҵĳ�ֵ�ȼ�
		$lvRes = $this->load('cls_role','mmoarpg')->records(array('field'=>'role_id','val'=>$role_id),'','','','','','level');
		if(!empty($lvRes)){
			$lv = $lvRes[0]['level'];
		}else{
			$lv = 1;  //�޷���ѯ����ҵĵȼ����������Ӧ�ò�����
		}

		echo " gggggggggggggggg cls_role  role_level: ".$lv."<br>";

		//��鶩����Ψһ��
		$totalcharge = $this->load('cls_charge','mmoarpg')->records(array('field'=>'paynum','val'=>$order_no),'','','','',true);
		echo "ccc totalcharge: ".$totalcharge."<br>";
		if($totalcharge){
			echo "######public function getExchange cls_charge is exist order_no: ".$order_no."<br>";
			echo "111c totalcharge: ".$totalcharge."<br>";
			//��ֵʧ��д����־
			$errorlog .= "\tʧ��ԭ�򣺶����ظ� \r\n";
			$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
			return $this->result(0,'','','2');   //�����ظ���
		}

		echo " public function getExchange cls_accountgold   <br>";
		//��ѯԪ����
		$accountinfo = $this->load('cls_accountgold','mmoarpg')->records(array('field'=>'plat_user_name','val'=>$plat_user_name),'','','','','','gold,gold_history');
		$log = "[".date("Y-m-d H:i:s")."]"."\tplat_user_name:".$plat_user_name."\trole_id:".$role_id."\trole_name:".$role_name."\tpaynum:".$post['order_id']."\tmoney:".$post['order_amount']."\tgold:".$post['gold']."\ttime:".$post['time']."[".date("Y-m-d H:i:s",$post['time'])."]";
		//echo "public function getExchange  $accountinfo  <br>"
		if(empty($accountinfo)){//��������ڳ�ֵ��¼�����²���
			echo "public function getExchange accountinfo  is NULL <br>";
			$paccount = array(
				'plat_user_name'=>$plat_user_name,
				'gold'=>$post['gold'],
				'gold_history'=>$post['gold'],
				'last_get_gold_time'=>0,
				'role_id'=>$role_id,
			);
			//��������
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$insert = $this->load('cls_accountgold','mmoarpg')->insert($paccount);
			if(!$insert){
				//��ֵʧ��д����־���ع�����
				echo "��ֵʧ��д����־���ع�����";
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "\tʧ��ԭ�򣺳�ֵʧ�ܣ��²������ݿ��[accountgold]ʧ�ܣ� \r\n";
				$this->error($log,'api.pay.'.date('Ymd'),0,'pay/');
				return $this->result(0,'','','0'); //��ֵʧ��
			}
			echo "insert success insert =  $insert<br>";
		}else{//���򣬸��¼�¼
			echo "public function getExchange accountinfo  is have <br>";
			$paccount = array(
			   'gold'=>$accountinfo[0]['gold'] + intval($post['gold']),//��������Ӧ�����Ϊ0������Ϊ�˷�ֹ������ֵ����Ҫ��ԭ����ֵ�����µ�Ԫ��
			   'gold_history'=>$accountinfo[0]['gold_history'] + intval($post['gold']),
			);
			echo "accountinfo[0][gold] =  ".$accountinfo[0]['gold']."<br>";
			echo "post[gold] =  ".$post['gold']."<br>";
			echo "paccount[gold] =  ".$paccount['gold']."<br>";

			//��������
			$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
			$update = $this->load('cls_accountgold','mmoarpg')->update($paccount,array('field'=>'plat_user_name','val'=>$plat_user_name));
			if(!$update){
				//��ֵʧ��д����־���ع�����
				$this->load('cls_accountgold','mmoarpg')->rollback();
				$log .= "\tʧ��ԭ�򣺳�ֵʧ�ܣ��������ݿ��[accountgold]ʧ�ܣ�\r\n";
				$this->error($log,'api.pay.'.date('Ymd').'.fail',0,'pay/');
				return $this->result(0,'','','0'); //��ֵʧ��
			}
		}
		$this->load('cls_accountgold','mmoarpg')->commit();

		$pcharge = array(
			'server_id' => 11111,        //��ID
			'paynum'	=> $order_no, 	//������							
			//  'mode'		=> 1, 		//��ֵ��ʽ
			//  'user'		=> 123456, 	    //��ֵƽ̨�ʺ�
			// 'role_id'	=> $role_id, 			//��ɫID
			// 'role_name' => $role_name, 			//��ɫ����
			// 'money'		=> 999, 		//��ֵ���
			// 'gold'		=> intval($post['gold']),		//��ֵԪ��
			// 'time'		=> THIS_DATETIME, 		//��ֵʱ��
			// 'ticket'	=> asd, 	//��ֵ��֤��
			// 'ip'		=> 123.435.234.123, 		//��ֵIP
			// 'result'	=> 8,	//��ֵ���,�ɹ�Ϊ8
			// 'lv'        => $lv,  //��ֵ��ɫ�ȼ�
		);

		//�鿴�����Ƿ���ڣ����ǲ�������ִ�в������������ع�֮ǰ�Ĳ���
		echo "public function getExchange cls_charge  check <br>";
		$totalcharge = $this->load('cls_charge','mmoarpg')->records(array('field'=>'paynum','val'=>$order_no),'','','','',true);
		echo "aaa totalcharge: ".$totalcharge."<br>";
		if($totalcharge){
		 	echo "222  public function getExchange cls_charge  is exist <br>";
		 	//�ع�����
		 	$this->load('cls_accountgold','mmoarpg')->rollback();
		 	//��ֵʧ��д����־
		 	$errorlog .= "\tʧ��ԭ�򣺶����ظ� \r\n";
		 	$this->error($errorlog,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		 	return $this->result(0,'','','2');   //�����ظ���
		}
		echo "public function getExchange cls_charge  insert  <br>";
		$pinsert = 0;
		$pinsert = $this->load('cls_charge','mmoarpg')->insert($pcharge);
		echo "bbb pinsert: ".$pinsert."<br>";
		if(!$pinsert){
		 	echo "public function getExchange cls_charge  insert  failed !!!! <br>";
		 	$log .= "\tʧ��ԭ�򣺺�̨���ɶ���ʧ��\tvalue:".var_export($pcharge,true)."\r\n";
		 	$this->error($log,'api.pay.'.date('Ymd').'.admin.fail',0,'pay/');
		 	//��������ʧ�ܣ��ع�����
		 	$this->load('cls_accountgold','mmoarpg')->rollback();
		 	return $this->result(0,'','','0'); //��ֵʧ��
		}
		//��������Ż���ֵ����ʱ��
		$pcommand = array(
			'creator' => 'background',		// ��̨
			'createtime' => THIS_DATETIME,
			'type'	=> 2,					
			'cmd' => "newnotice 3 {$role_id}",
		);
		echo "public function getExchange cls_command  insert  <br>";
		$cinsert = $this->load('cls_command','mmoarpg')->insert($pcommand);
		//�������ɳɹ���ִ������
		$this->load('cls_accountgold','mmoarpg')->commit();
		$log .= "\t��ֵ�ɹ� \r\n";
		$this->error($log,'api.pay.'.date('Ymd').'.succ',0,'pay/');
		return $this->result(1,'','','1');	
	}


	/**
	 * ���ܷ���
	 * $strEncode ����
	 * $keys ������Կ Ϊ��Ϸ����ʱ����� callback_key
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