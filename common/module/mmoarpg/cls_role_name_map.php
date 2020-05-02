<?php
class cls_role_name_map extends cls_model{
	protected $_table = 'role_name_map';
	
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
			$where=array('field'=>'idrole_name_map','val'=>intval($id));
			$res=$this->records($where,'','',1,1);
			return $res[0];
		}
	}
	
	public function setectinname($names,$roleid='',$field=''){
		if($roleid == ''){
			$condition = '`role_name`';
		}else{
			$condition = '`role_id`';
		}
		if($field == ''){
			$field = '`role_id`,`role_name`,`plat_user_name`';
		}
		$sql = "SELECT $field FROM $this->_table WHERE $condition IN ($names)";
		$query = $this->_db->query($sql);
		$rows = array();
		while ($info = $this->_db->fetcharray($query)) {
			$rows[] = $info;
		}
		return $rows;
	}
	
	/**
	 * 根据平台账号名获取值
	 * @param string $field
	 * @param unknown $plat_user_name
	 * @return multitype:unknown
	 */
	public function get_field_by_user($field = '*',$plat_user_name){
		$sql = "SELECT {$field} FROM {$this->_table} WHERE plat_user_name = '{$plat_user_name}' AND role_id>0";
		$query = $this->_db->query($sql);
		$rows = array();
		while ($info = $this->_db->fetcharray($query)) {
			$rows[] = $info;
		}
		return $rows;
	}

}
?>