<?php
class cls_command extends cls_model{
	protected $_table = 'command';
	
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
			$where=array('field'=>'idcommand','val'=>intval($id));
			$res=$this->records($where,'','',1,1);
			return $res[0];
		}
	}
	
	public function getlistbytype($type,$page,$pagesize){
		$sql = "SELECT * FROM $this->_table WHERE cmd LIKE '$type %' ORDER BY idcommand DESC LIMIT ".($page-1)*$pagesize.', '.$pagesize;
		$query = $this->_db->query($sql);
		$rows = array();
		while ($info = $this->_db->fetcharray($query)) {
             $rows[] = $info;			
		}
		return $rows;
	}

}
?>