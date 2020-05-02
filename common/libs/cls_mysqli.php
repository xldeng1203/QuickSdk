<?php
/**
@desciption: mysqli数据库类
*/

class cls_mysqli
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
		if(empty($this->conn) || $force) {
			$this->conn = mysqli_connect($host, $user, $password,$db) or die('Could not connect to database');
		
			if ($this->conn){
				$this->conn->set_charset('utf8');
			}
		}

		return $this->conn;
	}
	
	public function SelectDB($db,$conn=false){
		if($conn !== false) $this->conn = $conn;
		if ($this->conn){
			return $this->conn->select_db($db) or die ("Could not select database");
		}else{
			return false;
		}
	}

	public function Query($sql, $conn = '', $rollback = false){
		if(COMM_BENCHMARK) $benchmark_start = microtime(true);
		if(empty($conn)) $conn = $this->conn;
		$this->count++; //计数
		if($rs = mysqli_query($conn,$sql)) {
			if(COMM_BENCHMARK){
				$GLOBALS['benchmark'][$GLOBALS['benchmark_current']]['query'][] = array('sql'=>$sql,'runtime'=>microtime(true) - $benchmark_start);
			}
			return $rs;
		}else{
			if($rollback) $this->RollBack();
			if(COMM_DEBUG) {
				$this->log('query_error: ' . mysqli_error($conn) . ' sql: ' . $sql, "common/cls_mysql");
			}
			die(mysqli_error($conn));
		}
	}

	public function RollBack(){
		//只适用于InnoDB
		$this->conn->rollback();
		//die("MySQL ROLLBACK;");
	}

	public function NumRows($result){
		return $result->num_rows;
	}

	public function FetchRow($result){
		return $result->fetch_row();
	}
	
	public function FetchAssoc($result){
		return $result->fetch_assoc();
	}

	public function FetchArray($result){
		return $result->fetch_array(MYSQLI_ASSOC);
	}
	
	public function FetchObject($result){
		return $result->fetch_object();
	}

	public function FreeResult($result){
		return $result->free_result();
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
		return $this->conn->affected_rows;
	}
	
	public function DataSeek($result, $offset = 0){
	//复位记录集指针
		return $result->data_seek($offset);
	}
	
	public function InsertID(){
		echo "22 InsertID() <br>";
		return $this->conn->insert_id;
	}
	
	//mysql_result
	public function Result($result, $row = 0, $field = 0) {
		$this->DataSeek($result,$row);
		$data = $this->FetchRow($result);
		if(empty($data[$field])) {
			return null;
		}else{
			return $data[$field];
		}
	}
	
	public function Close(){
		if($this->conn){
			$this->conn->close();
		}
	}
}
?>