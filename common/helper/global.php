<?php

/**
 * 获取客户端ip
 *
 * @return string
 */
if(!function_exists('getip')){
	function getip(){
		$onlineip="127.0.0.1";
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}
}

/**
 * 返回文件扩展名（不返回'.'）
 * @param 文件名 $filename
 * @return string
 */
if(!function_exists('file_ext')){
	function file_ext($filename) {
		$pathinfo=pathinfo($filename);
		return strtolower($pathinfo['extension']);
	}
}

/**
 * 生成随机字符串(去除了0oO) 
 *
 * @param Int:$length
 * @return Strine
 */
if(!function_exists('rands')){
	function rands($length=4, $type=1) {
		$hash = '';
		switch ($type) {
			case 2: 
				$chars = 'abcdefghijklmnpqrstuvwxyz';
				break;
			case 3:	
				$chars = '123456789abcdefghijklmnpqrstuvwxyz';
				break;
			case 4: 
				$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
				break;
			case 5: 
				$chars = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
				break;
			case 6: 
				$chars = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
				break;
			case 7: 
				$chars = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
				break;
			default:
				$chars = '0123456789';
				break;
		}
		$max = strlen($chars) - 1;
		mt_srand((double)microtime() * 1000000);
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}
}

/**
 * utf8计算字符的个数(utf8字符 一个汉字返回长度为1 一个字母也是1)
 * @param string $str 
 * @return int
 */
if(!function_exists('utf8_strlen')){
	function utf8_strlen($str) {
	  $count = 0;
	  for ($i = 0; $i < strlen($str); ++$i) {
		if ((ord($str[$i]) & 0xC0) != 0x80) {
		  ++$count;
		}
	  }
	  return $count;
	}
}

/**
 * 返回日期时间差
 * @param string $type	y:年, m:月, d:日, h:时, i:分, s:秒
 * @param int $start
 * @param int $end
 * @return int
 */
if(!function_exists('datediff')){
	function datediff($type,$start,$end){
		switch ($type){
			case 'y':
				$diff=round(($end-$start)/31536000);
				break;
			case 'm':
				$diff=round(($end-$start)/2592000);;
				break;
			case 'd':
				$diff=round(($end-$start)/86400);
				break;
			case 'h':
				$diff=round(($end-$start)/3600);
				break;
			case 'i':
				$diff=round(($end-$start)/60);
				break;
			case 's':
				$diff=$end-$start;
				break;
		}
		return $diff;
	}
}


/**
 * 按数组字段排序（下标不变顺序变）
 * @param array $multArray 要被排序的数组
 * @param string $sortField 要被排序的字段
 * @param bol $asc 正排/倒排 （默认正排）
 * @return array
 */
if(!function_exists('sortbyfield')){
	function sortbyfield($multArray,$sortField,$asc=true)
	{
		$tmpKey='';
		$ResArray=array();
		$maIndex=array_keys($multArray);
		$maSize=count($multArray)-1;
		for($i=0; $i < $maSize ; $i++) {
			$minElement=$i;
			$tempMin=$multArray[$maIndex[$i]][$sortField];
			$tmpKey=$maIndex[$i];
			for($j=$i+1; $j <= $maSize; $j++){
			  if($multArray[$maIndex[$j]][$sortField] < $tempMin ) {
				 $minElement=$j;
				 $tmpKey=$maIndex[$j];
				 $tempMin=$multArray[$maIndex[$j]][$sortField];
			  }
			  $maIndex[$minElement]=$maIndex[$i];
			  $maIndex[$i]=$tmpKey;
			}
		}
	   if($asc){
		   for($j=0;$j<=$maSize;$j++){
			  $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];
		   }
	   }else{
		  for($j=$maSize;$j>=0;$j--){
			  $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];
		  }
	   }
	   return $ResArray;
	}
}


?>