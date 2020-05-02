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
		echo "--------------quicksdkAsy.php  OnQuickPay  begin-----------------------------------------------<br>";
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