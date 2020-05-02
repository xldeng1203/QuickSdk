<?php
class cls_accountgold extends cls_model{
	protected $_table = 'accountgold';
	
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
			$where=array('field'=>'accountgold_id','val'=>intval($id));
			$res=$this->records($where,'','',1,1);
			return $res[0];
		}
	}


}
?>