<?php
/**
@desciption: mysql数据库类
*/

class cls_mysql
{
	private static $conn = null;
	public static $count = 0;
	
	private static function log($msg, $file, $ok = false) {
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
	
	public function __construct($host, $user, $password, $db, $force = false) {		
		$this->connect($host, $user, $password, $db, $force);
	}
	
	public function __destruct(){
		$this->Close();
	}
	
	public function connect($host='', $user='', $password='',$db='', $force = false) {
		if($force) {
			$this->conn = mysql_connect($host, $user, $password, true) or die('Could not connect to database');
		
			if ($this->conn){
				$this->SelectDB($db);
				@mysql_query("SET NAMES 'utf8'",$this->conn);
			}
		}else{
			if (empty($this->conn)){
				$this->conn = $this->connect($host, $user, $password ,$db , true);
			}
		}

		return $this->conn;
	}
	
	public function SelectDB($db,$conn=false){
		if($conn !== false) $this->conn = $conn;
		if ($this->conn){
			return mysql_select_db($db, $this->conn) or die ("Could not select database");
		}else{
			return false;
		}
	}

	public function Query($sql, $conn = '', $rollback = false){
		if(COMM_BENCHMARK) $benchmark_start = microtime(true);
		if(empty($conn)) $conn = $this->conn;
		
		$this->count++; //计数
		
		if ($rs = mysql_query($sql,$conn)){
			if(COMM_BENCHMARK){
				echo "function Query  COMM_BENCHMARK: <br>";
				$GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['query'][] = array('sql'=>$sql,'runtime'=>microtime(true) - $benchmark_start);
			}
			echo "function Query  sql: ".$sql."<br>";
			echo "function Query  rs : ".$rs."<br>";
			return $rs;
		}else{
			if($rollback) $this->RollBack();
			if(COMM_DEBUG) {
				$this->log('query_error: ' . mysql_error($conn) . ' sql: ' . $sql, "common/cls_mysql");
			}	
			die(mysql_error($conn));

		}
	}

	public function RollBack(){
		//只适用于InnoDB
		$this->Query("ROLLBACK;");
		//die("MySQL ROLLBACK;");
	}

	public function NumRows($result){
		return mysql_num_rows($result);
	}

	public function FetchRow($result){
		return @mysql_fetch_row($result);
	}
	
	public function FetchAssoc($result){
		return @mysql_fetch_assoc($result);
	}

	public function FetchArray($result){
		return @mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	public function FetchObject($result){
		return @mysql_fetch_object($result);
	}

	public function FreeResult($result){
		return @mysql_free_result($result);
	}
	
	public function FetchAll($result){
		$rows = array();
		$this->DataSeek($result);
		while($row = $this->FetchAssoc($result)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	//影响行数
	public function AffectedRows(){
		return @mysql_affected_rows($this->conn);
	}
	
	public function DataSeek($result, $offset = 0){
	//复位记录集指针
		return @mysql_data_seek($result,$offset);
	}
	
	public function InsertID(){
		echo "11 InsertID() $this->conn<br>";
		return @mysql_insert_id($this->conn);
	}
	
	//mysql_result
	public function Result($result, $row = 0) {
		return @mysql_result($result, $row);
	}
	
	public function Close(){
		if($this->conn){
			@mysql_close($this->conn);
		}
	}
}
?>