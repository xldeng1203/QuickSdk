<?php
class cls_role extends cls_model{
	protected $_table = 'role';
	
	function __construct(&$db){
		$this->_table=$this->table($this->_table);
		$this->_db=$db;
	}
	
	/**
	 * 通过ID获取记录
	 * @param int $id
	 * @return array
	 */
	public function getrecordbyid($id){
		if (empty($id)) {
			return null;
		}else{
			$where=array('field'=>'role_id','val'=>intval($id));
			$res=$this->records($where,'','',1,1);
			return $res[0];
		}
	}
	
	
	public function getlistbysex($country = true){
		if($country){
		   $sql = "SELECT country, sex, count(*) AS num FROM $this->_table WHERE create_time > 0 AND level > 0 GROUP BY country, sex";
		}else{
		   $sql = "SELECT professional, sex, count(*) AS num FROM $this->_table WHERE create_time > 0 AND level > 0 GROUP BY professional, sex";
		}
		$query = $this->_db->query($sql);
		$rows = array();
		while ($info = $this->_db->fetcharray($query)) {
               $rows[] = $info;			
		}
		return $rows;
	}
	
	public function getlistbylevel($where=''){
		if($where == ''){
			$where = " WHERE `role_id` > 0 ";
		}
		$sql = "SELECT level, count(*) as num FROM $this->_table $where GROUP BY `level`";
		$query = $this->_db->query($sql);
		$rows = array();
		while ($info = $this->_db->fetcharray($query)) {
               $rows[$info['level']] = $info['num'];			
		}
		return $rows;
	}
	
	public function getlistbyscene($where=''){
		if($where == ''){
			$where = 'WHERE create_time > 0';
		}
		$sql = "SELECT scene_id, count(*) as num FROM $this->_table $where GROUP BY `scene_id`";
		$query = $this->_db->query($sql);
		$rows = array();
		while($info = $this->_db->fetcharray($query)){
			$rows[$info['scene_id']] = $info['num'];
		}
		return $rows;
	}
	public function getonlineinstant($where = '') {
		$result = array (
				"online" => 0,
				"client_online" => 0,
				"timestamp" => THIS_DATETIME 
		);
		$onlinetime = THIS_DATETIME - (3 * 60 + 10);
		$sql = "SELECT COUNT(*) as num FROM $this->_table WHERE `is_online` = 1 AND `last_save_time` > $onlinetime AND `create_time` > 0 ";
		$query = $this->_db->query ( $sql );
		$info = $this->_db->fetcharray ( $query );
		if (count ( $info ) > 0) {
			$result ['online'] = $info ['num'];
		}
		$sql = "SELECT COUNT(*) as num FROM $this->_table WHERE `is_online` = 1 AND `last_save_time` > $onlinetime AND `create_time` > 0 AND `is_micro_pc` != 0";
		$query = $this->_db->query ( $sql );
		$info = $this->_db->fetcharray ( $query );
		if (count ( $info ) > 0) {
			$result ['client_online'] = $info ['num'];
		}
		
		return $result;
	}
	
	/**
	 * 返回大于某个等级的角色总数
	 * 
	 * @author liguoyi
	 * @param string $where
	 * @return multitype:unknown
	 */
	public function getrolelevelcount($where=''){
		if($where == ''){
			$where = " WHERE `role_id` > 0";
		}
		$sql = "SELECT  count(*) as total FROM $this->_table $where ";
		$query = $this->_db->query($sql);
		$total = 0;
		while ($info = $this->_db->fetcharray($query)) {
			$total = $info['total'];
		}
		return $total;
	}
	
	/**
	 * 返回角色总数
	 * 
	 * @author liguoyi
	 * @param string $where
	 * @return int
	 */
	public function getrolecount($where=''){
		$sql = "SELECT  count(*) as total FROM $this->_table $where ";
		$query = $this->_db->query($sql);
		$total = 0;
		while ($info = $this->_db->fetcharray($query)) {
			$total = $info['total'];
		}
		return $total;
	}
}
?>