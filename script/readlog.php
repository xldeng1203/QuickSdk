<?php
/*
 * 读取游戏产生的log，后台数据的来源
 */

define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
require_once (Global_DIR.'configure/url.php');
require_once (Global_DIR.'configure/log.php');
ini_set ('memory_limit', '2048M');

$log_dir = $g_c["log_dir"];
$dataType = $g_c["data_type"];
	
function init_file_name($log_dir,$log_type){
	foreach($log_dir as $log_dir){
		if(isset($log_dir[$log_type])){
			if(file_exists($log_dir[$log_type]) == False) {//如果目录不存在
				$strcmd="mkdir -p {$log_dir[$log_type]}"; //建立多层目录
				exec($strcmd);
			}
			
			$chmod_r_log = $log_type.'*'; //要操作的日志
			if(file_exists($log_dir[$log_type].$log_type.'.txt')){
				$strcmd="chmod +r {$log_dir[$log_type]}{$chmod_r_log}"; //赋予日志文件读的权限
				exec($strcmd);
			} 
			
			if ($dir_handle = opendir($log_dir[$log_type])){
				while ($file_name = readdir($dir_handle)) {//返回文件名称
					if($file_name != "." && $file_name != ".."){
						$all_log_name[$log_dir[$log_type].$file_name]= $file_name;
					}			
				}   
				closedir($dir_handle);
			}
		
			//提取已经写完的日志,就是只提取名字中带有_的文件
			$exp_log_type = $log_type.'_';
			$log = array();
			if(is_array($all_log_name) && count($all_log_name)>0){
				foreach($all_log_name as $key => $value){
					if(strncasecmp($value,$exp_log_type,strlen($exp_log_type)) == 0){
						$log[$log_type][$key]=$value;
					}
				}
			}
		}
	}
	return $log;
}

function resolve_log($log,$log_type){
	$empty_log = TRUE;
	global $dataType;
	$stall = array();
	if(is_array($log[$log_type]) && count($log[$log_type]) > 0){
		foreach($log[$log_type] as $key => $value){
			if(filesize($key) > 0){
				$empty_log = FALSE;
			}
			$data = logread($key);
			if(is_array($data) && count($data) > 0){
				$stall[$key] = resolveLog($data, $dataType[$log_type]);	
			}
		}
	}
	$index=0;
	$stall_data = array();
	if(is_array($stall) && count($stall) > 0){
		foreach($stall as $value_stall){
			if(count($value_stall)==0) continue;
			foreach($value_stall as $value){
				$stall_data[$index++] = $value;
			}
		}
	}
	if (!$empty_log)
	{
		$filename = constant('PATH_ADMIN_LOG') . "/readlog.err";
		$text = $log_type;
		$text = strftime ("[%Y-%m-%d %H:%M:%S] ") . $text . "\n";
		$h = fopen($filename ,'a');
		if (!$h){ throw new exception('Could not open logfile:'.$filename); } 
		if ( ! flock($h,LOCK_EX) ){ return false; } 
		if (fwrite($h,$text)===false) { throw new exception('Could not write to logfile:'.$filename); }
		flock($h, LOCK_UN);
		fclose($h);		
	}
	unset($stall);
	return $stall_data;
}

function slow_log($log) {
	$filename = constant ( 'PATH_ADMIN_LOG' ) . "/slow_log.txt";
	$text = strftime ( "[%Y-%m-%d %H:%M:%S] " ) . $log . "\n";
	$h = fopen ( $filename, 'a' );
	if (! $h) {
		throw new exception ( 'Could not open logfile:' . $filename );
	}
	if (! flock ( $h, LOCK_EX )) {
		return false;
	}
	if (fwrite ( $h, $text ) === false) {
		throw new exception ( 'Could not write to logfile:' . $filename );
	}
	flock ( $h, LOCK_UN );
	fclose ( $h );
}

/*
 * 读取log日志里的内容
 */
function logread($fname){
	$fp=fopen($fname,"r");
	if(!$fp){
		echo $fname;	
		exit;
	}
	$data = "";
	while(!feof($fp)){
		$data .= fread($fp,4096);
	}
	fclose($fp);
	$dataArr = explode("\n",$data);
	unset($data);
	return $dataArr;
}

/*
 * 进行数据匹配
 * $data: log日志里的一行一行数据
 * $type：对应的正则表达式匹配数组（自定义）
 */
function  resolveLog($data, $type){
	//购买物品 [Stall::Buy Succ][user[%d:%d %s] stall_name:%s buyer[%d:%d %s] buy_item_id:%d buy_num:%d price_gold:%u price_coin:%u need_gold:%u need_coin:%u tax:%f add_gold:%u add_coin:%u
	//购买宠物失败 [Stall::BuyPet Discard_Pet_Fail][user[%d:%d %s] stall_name:%s buyer[%d:%d %s] buy_pet_id:%d buy_pet_index:%d need_gold:%u need_coin:%u tax:%f add_gold:%u add_coin:%u]
	//购买宠物成功 	[Stall::BuyPet Succ][user[%d:%d %s] stall_name:%s buyer[%d:%d %s] buy_pet_id:%d buy_pet_index:%d need_gold:%u need_coin:%u tax:%f add_gold:%u add_coin:%u]
	//购买银两	[Stall::BuyCoin Succ][user[%d:%d %s] stall_name:%s buyer[%d:%d %s] buy_coin:%u need_gold:%u tax:%f add_goold:%u]

	$index=0;
	$trade_data = array();
	$count = count($data);
	for($i = 0;$i<$count;$i++){
		preg_match($type['type_exp'],$data[$i], $trade_info); //返回要匹配的正则表达式的key（自定义了正则表达式数组）
		foreach($type['op_type'] as $key => $value){
			if(count($trade_info) > 0 && strcmp($trade_info[1], $key) == 0 && !empty($data[$i])){//如果key相等，则使用该正则去匹配log日志的内容
				preg_match($value['exp'], $data[$i], $exp_data); //把匹配后符合的内容装载在数组$exp_data里
				if(count($exp_data) > 0){
					$index_exp=0;
					foreach($value['data'] as $value ){//循环设定的数据字段，把对应的内容赋值给字段
						if(strstr($value,'timestamp')){
							$trade_data[$index][$value]=strtotime($exp_data[++$index_exp]);
						}else{
							$trade_data[$index][$value]=$exp_data[++$index_exp];
						}
					}
					$index++;
				}
			}
		}
	}
	unset($data);
	return $trade_data;
}

/*
 * 把log得到的数据入库
 */
function  insert_data_to_db($data,$table_name){
	if(is_array($data) && count($data) > 0){
		foreach($data as $value){
			cls_entry::load($table_name)->add($value);
		}
		return true;
	}else{
		return false;
	}
}

/**
 * 针对玩家行为监控表进行的数据库删除操作,先删除后插入
 */
function delete_data_to_db($data,$table_name){
	if(is_array($data) && count($data) > 0){
		foreach($data as $value){
			if(!isset($value['role_id'])) $value['role_id'] = 0;
			$where = array('field'=>'role_id','val'=>$value['role_id']);
			cls_entry::load($table_name)->del($where);
			cls_entry::load($table_name)->add($value);
		}
	}
}

/*
 * 把已经读取的log文件移到另外一个文件夹
 */
function  bak_log($log,$log_type,$mkdir_name = ''){
	global $g_c;
	
	//如果是游戏日志，则必须分目录备份
	$log_bak = $g_c["log_bak"];
	$log_bak = empty($mkdir_name) ? $log_bak : $log_bak.$mkdir_name."/"; //备份已经读取的log日志的路径

	if(file_exists($log_bak) == False){//如果目录不存在
		$strcmd="mkdir -p {$log_bak}"; //建立多层目录
		exec($strcmd);
	}

	if(count($log[$log_type]) == 0) return -1;
	$index = 1;
	$time = time();
	foreach($log[$log_type] as $key => $value){				
		$filename = str_replace('.txt', '', $value);
		$log_bak_change = $log_bak.$filename.'_'.$index.'.txt';
		//若文件为空，直接删除，无需保存
		if(filesize($key) == 0){
			$rmcmd = "rm ".$key." -f";
			exec($rmcmd);
		}
		//不为空则转移到备份文件夹
		//防止重名文件被替换，对文件加上索引进行重命名
		else{
			$strcmd="mv ".$key." {$log_bak_change}";
			exec($strcmd);
		}
		$index++;
	}
}

/*
 * 下面进行各类日志数据的提取，格式如下：
 * $log = init_file_name(日志文件的路径,'日志文件的名称前缀(也是文件路径数组的key)');
 * $data = resolve_log($log,'要进行匹配的正则表达式');
 * insert_data_to_db($data,'要插入数据的表');
 * bak_log($log, '日志文件的名称前缀', '备份日志文件夹的名称');
 * 备注：日志文件的名称前缀、要进行匹配的正则表达式的名称要一致
 */

if($argv[1] == 86400){//$argv 返回命令行的参数： 86400
	//获取《在线统计》栏目中的《历史在线》数据
	$log = init_file_name($log_dir,'rolenum');
	$data = resolve_log($log,'rolenum');
	insert_data_to_db($data,'log_instant_online');
	bak_log($log, 'rolenum', 'loginlog');
	unset($log); unset($data);
	echo"获取玩家历史在线数据(rolenum)-完成\n";
}else{
	//获取元宝商城的数据
	$log = init_file_name($log_dir,'shop');
	$data = resolve_log($log,'shop');
	insert_data_to_db($data,'log_gold_shop');
	bak_log($log, 'shop', 'shop');
	unset($log); unset($data);
	echo"获取元宝商城的数据(shop)-完成\n";
	
	//获取玩家元宝流动数据
	$log = init_file_name($log_dir,'money_gold');
	$data = resolve_log($log,'money_gold');
	insert_data_to_db($data,'log_money_gold');
	bak_log($log, 'money_gold', 'money_gold');
	unset($log); unset($data);
	echo"获取玩家元宝流动数据(money_gold)-完成\n";
	
	//获取功能活跃度统计数据
	$log = init_file_name($log_dir, 'functionstats');
	$data = resolve_log($log, 'functionstats');
	insert_data_to_db($data,'log_functionstats');
	bak_log($log, 'functionstats', 'functionstats');
	unset($log); unset($data);
	echo"获取功能活跃度统计数据(functionstats)-完成\n";
	
	//祈福开宝箱日志
	$log = init_file_name($log_dir, 'chestshop');
	$data = resolve_log($log, 'chestshop');
	insert_data_to_db($data,'log_chestshop');
	bak_log($log, 'chestshop', 'chestshop');
	unset($log); unset($data);
	echo"获取祈福日志数据(chestshop)-完成\n";
	

	//坐骑相关操作日志
	$log = init_file_name($log_dir, 'mount');
	$data = resolve_log($log, 'mount');
	insert_data_to_db($data,'log_mount');
	bak_log($log, 'mount', 'mount');
	unset($log); unset($data);
	echo"获取玩家坐骑操作日志数据(userstate)-完成\n";	

	//玩家断线日志
	$log = init_file_name($log_dir, 'disconnect');
	$data = resolve_log($log, 'disconnect');
	insert_data_to_db($data,'log_disconnect');
	bak_log($log, 'disconnect', 'disconnect');
	unset($log); unset($data);
	echo"获取玩家断线日志数据(disconnect)-完成\n";

	//宠物相关操作日志
	$log = init_file_name($log_dir, 'pet');
	$data = resolve_log($log, 'pet');
	insert_data_to_db($data,'log_pet');
	bak_log($log, 'pet', 'pet');
	unset($log); unset($data);
	echo"获取玩家宠物操作日志数据(pet)-完成\n";

	//装备相关操作日志
	$log = init_file_name($log_dir, 'equipment');
	$data = resolve_log($log, 'equipment');
	insert_data_to_db($data,'log_equipment');
	bak_log($log, 'equipment', 'equipment');
	unset($log); unset($data);
	echo"获取玩家装备操作日志数据(equipment)-完成\n";	
	
	//获取物品合成的数据
	$log = init_file_name($log_dir,'item');
	$data = resolve_log($log,'item');
	insert_data_to_db($data,'log_item');
	bak_log($log, 'item', 'item');
	unset($log); unset($data);
	echo"获取物品合成的数据(item)-完成\n";	

	//玩家邮件日志
	$log = init_file_name($log_dir,'mail');
	$data = resolve_log($log,'mail');
	insert_data_to_db($data,'log_mail');
	bak_log($log, 'mail', 'mail');
	unset($log); unset($data);
	echo"获取物玩家邮件日志数据(mail)-完成\n";
	
	//拍卖日志
	$log = init_file_name($log_dir,'publicsale');
	$data = resolve_log($log,'publicsale');
	insert_data_to_db($data,'log_publicsale');
	bak_log($log, 'publicsale', 'publicsale');
	unset($log); unset($data);
	echo"获取拍卖日志(publicsale)-完成\n";

	//世界BOSS掉落日志
	$log = init_file_name($log_dir,'drop');
	$data = resolve_log($log,'drop');
	insert_data_to_db($data,'log_drop');
	bak_log($log, 'drop', 'drop');
	unset($log); unset($data);
	echo"获取世界BOSS掉落日志(drop)-完成\n";	
	
	//玩家升级日志
	$log = init_file_name($log_dir,'role_upgrade');
	$data = resolve_log($log,'role_upgrade');
	insert_data_to_db($data,'log_upgrade');
	bak_log($log, 'role_upgrade', 'role_upgrade');
	unset($log); unset($data);
	echo"获取物玩家升级记录数据(role_upgrade)-完成\n";	

	//玩家登录下线日志
	$log = init_file_name($log_dir, 'userstate');
	$data = resolve_log($log, 'userstate');
	insert_data_to_db($data,'log_login');
	bak_log($log, 'userstate', 'userstate');
	unset($log); unset($data);
	echo"获取玩家登录日志数据(userstate)-完成\n";	
	
	//战场日志
	$log = init_file_name($log_dir, 'battlefield');
	$data = resolve_log($log, 'battlefield');
	insert_data_to_db($data,'log_battlefield');
	bak_log($log, 'battlefield', 'battlefield');
	unset($log); unset($data);
	echo"获取玩家战场日志数据(battlefield)-完成\n";

	//交易日志
	$log = init_file_name($log_dir, 'p2ptrade');
	$data = resolve_log($log, 'p2ptrade');
	insert_data_to_db($data,'log_p2ptrade');
	bak_log($log, 'p2ptrade', 'p2ptrade');
	unset($log); unset($data);
	echo"获取交易日志数据(p2ptrade)-完成\n";	

	//玩家行为监控
	$log = init_file_name($log_dir, 'monitor');
	$data = resolve_log($log, 'monitor');
	delete_data_to_db($data,'log_monitor');
	bak_log($log, 'monitor', 'monitor');
	unset($log); unset($data);
	echo"获取玩家行为日志数据(monitor)-完成\n";

	//境界提升日志
	$log = init_file_name($log_dir, 'mentality');
	$data = resolve_log($log, 'mentality');
	insert_data_to_db($data,'log_jingjie');
	bak_log($log, 'mentality', 'mentality');
	unset($log); unset($data);
	echo"获取玩家境界提升日志数据(mentality)-完成\n";
	
	//结婚日志
	$log = init_file_name($log_dir, 'marrylog');
	$data = resolve_log($log, 'marrylog');
	insert_data_to_db($data,'log_marry');
	bak_log($log, 'marrylog', 'marry');
	unset($log); unset($data);
	echo"获取玩家玩家结婚日志数据(marry)-完成\n";
	
	//获取背包操作中的物品使用、丢弃、消耗的数据
	$log = init_file_name($log_dir,'knapsack');
	$data = resolve_log($log,'knapsack');
	insert_data_to_db($data,'log_knapsack');
	bak_log($log, 'knapsack', 'knapsack');
	unset($log); unset($data);
	echo"获取物品使用、丢弃、消耗的数据(knapsack)-完成\n";	

	//获取玩家银两流动数据
	$log = init_file_name($log_dir,'money_coin');
	$data = resolve_log($log,'money_coin');
	insert_data_to_db($data,'log_money_coin');
	bak_log($log, 'money_coin', 'money_coin');
	unset($log); unset($data);
	echo"获取玩家银两流动数据(money_coin)-完成\n";	

	//获取玩家的任务数据
	$log = init_file_name($log_dir,'taskstats');
	$data = resolve_log($log,'taskstats');
	insert_data_to_db($data,'log_task');
	bak_log($log, 'taskstats', 'task_stast');
	unset($log); unset($data);
	echo"获取玩家的任务数据(taskstast)-完成\n";
}
exit("Finish\n");
?>
