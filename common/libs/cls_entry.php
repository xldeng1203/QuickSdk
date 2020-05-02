<?php
/**
@desciption: 公共入口
*/
class cls_entry{
	public static $object;
	/**
	 * 加载对象模块
	 *
	 * @param string $control	对象名
	 * @return object
	 */
	public static function load($control){
		if(COMM_BENCHMARK){
			$GLOBALS['benchmark_current'] = $control;
			$GLOBALS['benchmark'][$control]['start'] = microtime(true);
		}
		if(!empty(self::$object[$control]) && is_object(self::$object[$control])) {
			return self::$object[$control];
		}else{
			global $INCMAP;
			if(file_exists(COMM_DIR.$INCMAP[$control])){
				include_once(COMM_DIR.$INCMAP[$control]);
			}else{
				die('Can not load the class '.$control);
				//return false;
			}
			try {
				return self::$object[$control] = new $control();
			}catch (Exception $err){
				die($err->getMessage());
				//return false;
			}
		}
	}
	
	
	public static function benchmark(){
		if(COMM_BENCHMARK){
			print_r($GLOBALS['benchmark']);
		}else{
			die('Please open benchmark config');
		}
	}
}

?>