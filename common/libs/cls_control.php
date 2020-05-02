<?php
/**
 * 控制类
 *
 */
abstract class cls_control extends cls_validation
{
	protected static $obj;
	private static $_conn;
	
	/**
	 * 加载数据库模块
	 *
	 * @param string $module
	 * @param string $database
	 * @return object
	 */
	protected function load($module, $database = 'admintool'){
		if(!empty($this->obj[$module]) && is_object($this->obj[$module])) {
			return $this->obj[$module];
		}else{
			
			if(file_exists(COMM_DIR.'module/'.$database.'/'.$module.'.php')) {
				include_once(COMM_DIR.'module/'.$database.'/'.$module.'.php');
				try{
					return $this->obj[$module] = new $module($this->conn($database));
				}catch (Exception $err){
					$this->log($err->getMessage(), 'common/cls_control');
				}
			}else{
				$this->log('Can not load the module '.$module, 'common/cls_control');
			}
		}
	}
	
	/**
	 * 加载公共函数
	 *
	 * @param string $name
	 */
	protected function helper($name){
		if(file_exists(COMM_DIR.'helper/'.$name.'.php')) {
			include_once(COMM_DIR.'helper/'.$name.'.php');
		}else{
			$this->log('Can not load the helper '.$name, 'common/cls_control');
		}
	}
	
	/**
	 * 加载控制器配置文件
	 *
	 * @param string $control
	 */
	protected function config($control){
		if(file_exists(COMM_DIR.'conf/'.$control.'.config.php')) {
			include_once(COMM_DIR.'conf/'.$control.'.config.php');
		}else{
			$this->log('Can not load the config '.$control, 'common/cls_control');
		}
	}
	
	/**
	 * 建立数据库链接
	 *
	 * @param string $database
	 * @return link
	 */
	private function conn($database = 'admintool'){
		if(!empty($this->_conn[$database]) && is_object($this->_conn[$database])) return $this->_conn[$database];
		
		include(COMM_DIR.'conf/db.config.php');
		
		if(empty($DB_CONFIG[$database])) {
			$this->log("Database $database no set", 'common/cls_control');
		}
		
		if (version_compare ( PHP_VERSION, '5.5.0' ) < 0) {
			include_once ('cls_mysql.php');
			return $this->_conn [$database] = new cls_mysql ( $DB_CONFIG [$database] ['host'], $DB_CONFIG [$database] ['user'], $DB_CONFIG [$database] ['password'], $DB_CONFIG [$database] ['db'], true );
		} else {
			include_once ('cls_mysqli.php');
			return $this->_conn [$database] = new cls_mysqli ( $DB_CONFIG [$database] ['host'], $DB_CONFIG [$database] ['user'], $DB_CONFIG [$database] ['password'], $DB_CONFIG [$database] ['db'], true );
		}
	}
	
	/**
	 * 返回输出结果
	 *
	 * @param bool $status
	 * @param string $error
	 * @param array $valid
	 * @param mix $res
	 * @param int $count
	 * @param int $page
	 * @param int $size
	 * @param bool $json
	 * @param string $lang
	 * @return mix
	 */
	public function result($status, $error = '', $valid = '', $res = '', $count = '', $page = '', $size='', $json = false, $language = 'zh-cn'){
		$result['status'] = $status;
		if($error != ''){
			if(file_exists((COMM_DIR.'lang/'.$language.'.php'))){
				//include(COMM_DIR.'lang/'.$language.'.php');
				$result['error'] = isset($lang[$error])?$lang[$error]:$error;
			}else{
				//die('Can not find the language '.$language);
				$result['error'] = $error;
			}
		}
		if(!empty($valid) && is_array($valid)) $result['valid'] = $valid;
		if(is_numeric($count)) $result['count'] = $count;
		if(is_numeric($page)) $result['page'] = $page;
		if(is_numeric($size)) $result['size'] = $size;
		if($size > 0 && $count > 0) $result['pagecount'] = ceil($count/$size);
		$result['result'] = $res;
		if(COMM_BENCHMARK) {
			$GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['end'] = microtime(true);
			$GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['runtime'] = $GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['end'] - $GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['start'];
		}

		return $json?json_encode($result):$result;
	}
	
	/**
	 * 写错误日志
	 *
	 * @param string $message        	
	 * @param string $control        	
	 * @param bool $die        	
	 */
	protected function error($message, $control, $die = 0, $dir = 'log/') {
		$log_dir = PATH_ADMIN_LOG . "/" . $dir;

		if (! is_dir ( $log_dir )) {
			@mkdir ( $log_dir, 0777 );
		}

		error_log ( "[" . date ( "Y-m-d H:i:s" ) . "] " . $message, 3, $log_dir . $control . '.log' );

		if ($die) {
			die ( $message );
		}
	}
	protected static function log($msg, $file, $ok = false) {
		if ($ok) {
			$path = PATH_ADMIN_LOG . "/" . $file . '_' . date ( 'Ymd' ) . '.log';
		} else {
			$path = PATH_ADMIN_LOG . "/" . $file . '_' . date ( 'Ymd' ) . '.err';
		}
		
		$dir = dirname ( $path );
		
		if (! is_dir ( $dir )) {
			@mkdir ( $dir, 0777 );
		}
		
		if (is_array($msg)) {
			error_log ( "[" . date ( "Y-m-d H:i:s" ) . "] " . json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n", 3, $path );
		} else {
			error_log ( "[" . date ( "Y-m-d H:i:s" ) . "] " . $msg . "\n", 3, $path );
		}
	}
	
	/**
	 * 返回json 
	 * 
	 * @author liguoyi
	 * @param unknown $status 状态
	 * @param unknown $msg 描述
	 * @param unknown $data 结果
	 */
	public function response_json($status,$msg,$data=array()){
		return json_encode(array(
				'status'=>$status,
				'msg'=>$msg,
				'data'=>$data
		));
	}
}