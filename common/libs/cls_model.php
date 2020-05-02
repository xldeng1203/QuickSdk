<?php
/**
edesciption: 数据库模型，抽象类
*/

abstract class cls_model
{
	protected $_table = null;
	public $_db = null;//原保护型

	/**
     * 插入记录，成功返回插入ID
     *
     * @param array $arr	array('field'=>'value')
     * @param bool	$replace 替换
     * @return int
     */
	public function insert($arr, $replace=false){
		echo "public function insert <br>";
		if (!empty($arr) && is_array($arr)) {
			$arr=$this->_filter($arr);
			$fields=array();
			$values=array();
			foreach ($arr as $field=>$val){
				$fields[]="`$field`";
				$values[]="'$val'";
			}
			$fields=implode(',', $fields);
			$values=implode(',', $values);
			echo "11 public function insert  $this->_table <br>";
			$sql=($replace?'REPLACE':'INSERT').' INTO '.$this->_table."($fields)VALUES($values);";
			echo "22 public function insert  $sql <br>";
			if($this->_db->Query($sql)){
				echo "333 public function insert   <br>";
				return $this->_db->InsertID();
			}else{
				echo "44 public function insert   <br>";
				return 0;
			}
		}else{
			return 0;
		}
	}

	/**
     * 更新记录
     *
     * @param mix $arr	array('field'=>'value')
     * @param array $where	array('cond'=>'AND','field'=>'id','type'=>'=','val'=>10)
     * @return bool
     */
	public function update($arr, $where=''){
		if(!empty($arr) && !empty($where)){
			$arr=$this->_filter($arr);
			if (is_array($arr)) {
				$set=array();
				foreach ($arr as $field=>$val){
					$set[]="`$field`='$val'";
				}
				$set=implode(', ',$set);
			}else{
				$set=$arr;
			}

			$sql='UPDATE '.$this->_table." SET $set".$this->_where($where);
			
            $this->_db->Query($sql);
            
			return $this->_db->AffectedRows();
		}else{
			return false;
		}
	}

	/**
     * 删除记录
     *
     * @param array $where	array('cond'=>'AND','field'=>'id','type'=>'=','val'=>10)
     * @return bool
     */
	public function delete($where){
		if (!empty($where) && !empty($where)) {
			$sql='DELETE FROM '.$this->_table.$this->_where($where);

			return $this->_db->Query($sql);
		}else{
			return false;
		}
	}
	
	/**
     * sql执行
     *
     * @param $sql
     * @return bool
     */
	public function execsql($sql){
		if (!empty($sql)) {
			return $this->_db->Query($sql);
		}else{
			return false;
		}
	}
	
	/**
	 * sql执行并且获取结果集
	 * @param $sql
	 * @return array
	 */
	public function getArr($sql){
		if (!empty($sql)) {
			$rs = $this->_db->Query($sql);
			if(!$rs) return null;
			$_records=array();
			while ($row=$this->_db->FetchArray($rs)) {
				$_records[]=$row;
			}
			$this->_db->FreeResult($rs);
			return $_records;
		}else{
			return false;
		}		
	}

	/**
     * 获取记录集合
     *
     * @param array $where		array('cond'=>'AND','field'=>'id','type'=>'=','val'=>10)
     * @param array $orderby	array('field'=>'id','type'=>'DESC')
     * @param array $join		array('table'=>'table','type'=>'INNER','left'=>'id','right'=>'p_id') 仅限于同一数据库
     * @param array $fields		array('field1','field2')
     * @param int $page			页
     * @param int $pagesize		分页大小
     * @param bool $count		计数
     * @return mix
     */
	public function &records($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 0, $count = false, $fields = '*'){
		$fields=$count?'count(*)':$this->_fields($fields);
		
		$sql='SELECT '.$fields.' FROM '.$this->_table.$this->_join($join).$this->_where($where);
		//echo $sql,'<br/>';die;
		if ($count) {
			$rs = $this->_db->Query($sql);
			$num = $this->_db->Result($rs,0);
			return $num;
		}
		
		$sql.=$this->_orderby($orderby);
		$sql.=empty($page) || empty($pagesize)?';':' LIMIT '.($page-1)*$pagesize.', '.$pagesize.';';
		//$start=microtime();
		$rs=$this->_db->Query($sql);
		if(!$rs) return null;
		//echo $sql,'<br/>';
		$_records=array();
		while ($row=$this->_db->FetchArray($rs)) {
			$_records[]=$row;
		}
		$this->_db->FreeResult($rs);
		//echo '<b>(',microtime()-$start,')</b><br/>';
		return $_records;
	}

	/**
     * 记录计数
     *
     * @param array $where	array('cond'=>'AND','field'=>'id','type'=>'=','val'=>10)
     * @param array $join	array('table'=>'table','type'=>'INNER','left'=>'id','right'=>'p_id') 仅限于同一数据库
     * @return int
     */
	public function count($where='', $join='') {
		return $this->records($where,'',$join,0,0,true);
	}
	
	/**
	 * 返回表名
	 *
	 * @param string $db		数据库
	 * @param string $table		表名
	 * @param string $prefix	前缀
	 * @return string
	 */
	public function table($table, $prefix = ''){
		return $prefix.$table;
	}
	
	/**
	 * 返回查询字段串
	 *
	 * @param mix $fields
	 * @return string
	 */
	private function _fields($fields){
		if(empty($fields)) {
			return '*';
		}elseif (is_array($fields)) {
			$fields=$this->_filter($fields);
			return join(',',$fields);
		}else{
			return $fields;
		}
	}

	/**
	 * 返回WHERE串
	 *
	 * @param array $where	array('cond'=>'AND','field'=>'id','type'=>'=','val'=>10)
	 * @return string
	 */
	private function _where($where){
		if (empty($where)) {
			return '';
		}elseif(is_array($where)){
			$where=$this->_filter($where);
			if ($this->is_multi_array($where)) {//多维数组
				$_where=' WHERE 1';
				foreach ($where as $w){
					if(empty($w['cond'])) $w['cond']='AND';
					if(empty($w['type'])) $w['type']='=';
					$_where.=$this->_condition($w['field'],$w['type'],$w['val'],$w['cond']);
				}
			}else{//一维数组
				if(empty($where['type'])) $where['type']='=';
				$_where=' WHERE'.$this->_condition($where['field'],$where['type'],$where['val']);
			}
			return $_where;
		}else{//字符串，需带WHERE
			return $where;
		}
	}

	/**
     * 返回ORDER BY串
     *
     * @param mix $arr	array('field'=>'id','type'=>'DESC')
     * @return string
     */
	private function _orderby($orderby){
		if (empty($orderby)) {
			return '';
		}elseif(is_array($orderby)) {
			$orderby=$this->_filter($orderby);
			if ($this->is_multi_array($orderby)) {//多维数组
				$_orderby=array();
				foreach ($orderby as $o){
					if(empty($o['type'])) $o['type']='';
					if (!empty($o['field'])) {
						$_orderby[]=$o['field'].' '.$o['type'];
					}
				}
				$_orderby=' ORDER BY '.implode(', ',$_orderby);
			}else{//一维数组
				if(empty($orderby['type'])) $orderby['type']='';
				$_orderby=" ORDER BY $orderby[field] $orderby[type]";
			}
			return $_orderby;
		}else{//字符串，需带ORDER BY
			return $orderby;
		}
	}
	
	/**
	 * 返回连接组合串
	 *
	 * @param array $join		array('table'=>'table','type'=>'INNER','left'=>'id','right'=>'p_id') 仅限于同一数据库
	 * @return string
	 */
	private function _join($join){
		if (empty($join)) {
			return '';
		}elseif(is_array($join)) {
			$join=$this->_filter($join);
			if ($this->is_multi_array($join)) {//多维数组
				$_join='';
				foreach ($join as $j){
					if(empty($j['type'])) $j['type']='INNER';
					if (!empty($j['table']) && !empty($j['left']) && !empty($j['right'])) {
						$_join.=" $j[type] JOIN $j[table] ON $j[left]=$j[right]";
					}
				}
			}else{//一维数组
				if(empty($join['type'])) $join['type']='INNER';
				$_join=" $join[type] JOIN $join[table] ON $join[left]=$join[right]";
			}
			return $_join;
		}else{//字符串，需带INNER JOIN
			return $join;
		}
	}

	/**
     * 返回数组是否为多维数组
     *
     * @param array $arr
     * @return bool
     */
	private function is_multi_array($arr){
		if (empty($arr)) {
			return false;
		}else{
			foreach ($arr as $a){
				if (is_array($a)) return true;
			}
			return false;
		}
	}

	/**
	 * 返回条件组合字符串
	 *
	 * @param string $field
	 * @param string $type			=,!=,>,<,>=,<=,like,in,not in
	 * @param string $value
	 * @param string $condition		AND,OR
	 * @return string
	 */
	private function _condition($field,$type,$value,$condition=''){
		if (!isset($field) || !isset($type)) {
			return '';
		}
		$_cond=empty($condition)?'':' '.$condition;
		switch (strtolower($type)) {
			case 'like':
				$_cond.=" $field LIKE '%$value%'";
				break;

			case 'in':
				$_cond.=" $field IN ($value)";
				break;

			case 'not in':
				$_cond.=" $field NOT IN ($value)";
				break;

			default:
				$_cond.=" $field $type '$value'";
				break;
		}
		
		return $_cond;
	}
	
	/**
	 * 安全过滤
	 *
	 * @param mix $string
	 * @return mix
	 */
	private function _filter($string) {
		if(empty($string)) return $string;
		if(is_array($string)) {
			foreach ($string as $key=>$str) {
				$string[$key] = $this->_filter($str);
			}
		}else{
			$string = stripslashes($string);
			$string = addslashes($string);
		}
		
		return $string;
	}
	
	/**
	 * 设置不自动提交
	 * 针对事务操作
	 */
	public function setAutoCommit(){
		$this->_db->Query("SET AUTOCOMMIT=0");
	}
	
	/**
	 * 事务开始
	 */
	public function begin(){
		$this->_db->Query("BEGIN");
	}
	
	/**
	 * 事务回滚
	 */
	public function rollback(){
		$this->_db->Query("ROLLBACK");
	}
	
	/**
	 * 执行事务
	 */
	public function commit(){
		$this->_db->Query("COMMIT");
	}
	
}
?>
