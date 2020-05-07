<?php
class cls_charge extends cls_model
{
	protected $_table = 'charge';

	function __construct(&$db){
		$this->_table=$this->table($this->_table);
		$this->_db=$db;
	}

	/**
	 * 通过ID获取记录
	 * @param int $id
	 * @return array
	 */
	public function getrecordbyid($id)
	{
		if (empty($id)) {
			return null;
		}else{
			$where=array('field'=>'id','val'=>intval($id));
			$res=$this->records($where,'','',1,1);
			return $res[0];
		}
	}

	public function getlistbyid($roleid = '',$rolename = '',$page = 1, $pagesize = 0,$count=false){
		$where = " where `mode` != 'admin' and result = 8";
		if($plat != ''){
			$where .= " and role_id = '$roleid' ";
		}
		if($rolename != ''){
			$where .= " and role_name = '$rolename' ";
		}
		if($count){
            $sql = "select count(*) from $this->_table {$where} group by role_id";
            $query = $this->_db->query($sql);
			$rows = array();
			while ($info = $this->_db->fetcharray($query)) {
				$rows[] = $info;
			}
            return count($rows);
		}else{
			$limit = empty($page) || empty($pagesize) ? ' ':' LIMIT '.($page-1)*$pagesize.', '.$pagesize.';';
			$sql = "select count(*) as count,`role_id`, `role_name`, sum(`money`) as money,sum(`gold`) as gold, max(`time`) as maxtime,min(`time`) as mintime from $this->_table {$where} group by role_id order by money desc $limit";

			$query = $this->_db->query($sql);
			$rows = array();
			while ($info = $this->_db->fetcharray($query)) {
				$rows[] = $info;
			}
			return $rows;
		}
	}
	
	public function getlistbyidmin($plat = '',$rolename = '',$page = 1, $pagesize = 0,$count=false,$month){
		$where = " where `mode` != 'admin' and result = 8";
		if($plat != ''){
			$where .= " and user = '$plat' ";
		}
		if($rolename != ''){
			$where .= " and role_name = '$rolename' ";
		}
		if($month){
			$st = strtotime($month.'-01 00:00:00');
			$et = strtotime('+1 month',$st)-1;
			$where .= " and time >= '$st' and time <= '$et'";
		}
		if($count){
            $sql = "select count(*) from $this->_table {$where} group by role_id";
            $query = $this->_db->query($sql);
			$rows = array();
			while ($info = $this->_db->fetcharray($query)) {
				$rows[] = $info;
			}
            return count($rows);
		}else{
			$limit = empty($page) || empty($pagesize) ? ' ':' LIMIT '.($page-1)*$pagesize.', '.$pagesize.';';
			$sql = "select `role_id`, min(`time`) as time from $this->_table {$where} group by role_id $limit";

			$query = $this->_db->query($sql);
			$rows = array();
			while ($info = $this->_db->fetcharray($query)) {
				$rows[] = $info;
			}
			return $rows;
		}
	}
	
	public function gettotal($begtime='',$endtime='',$field=''){
		$where = " where mode!='admin' and result = 8";
		if(!empty($begtime)){
			$where .= " AND time >= ".$begtime;
		}
		if(!empty($endtime)){
			$where .= " AND time <= ".$endtime;
		}
		if(empty($field)){
			$field = 'sum(`money`) as totalmoney,sum(`gold`) as totalgold,count(*) as count,count(distinct `role_id`) as player';
		}
		$sql = "select $field from $this->_table {$where}";
		$query = $this->_db->query($sql);
		if(!$query) return null;
		$rows = array();
		$info = $this->_db->FetchArray($query);
		$this->_db->FreeResult($query);
		return $info;		
	}
	
	public function getQuery($uidarr=''){
		//充值成功并且玩家为非内部人员
		$where = 'WHERE 1 ';
		$where .= " AND `mode` != 'admin' AND `result` = 8 ";
		if(!empty($uidarr)){
			$where .= " AND `role_id` IN ( $uidarr ) ";
		}
		$sql = "SELECT SUM(`money`) AS total,`role_id` FROM $this->_table $where GROUP BY `role_id`";
		$rs=$this->_db->Query($sql);
		if(!$rs) return null;
		$_records=array();
		while ($row=$this->_db->FetchArray($rs)) {
			$_records[$row['role_id']]=$row['total'];
		}
		$this->_db->FreeResult($rs);
		return $_records;
	}
	
	public function getQ($uidarr){
		//充值成功并且玩家为非内部人员
		$where = 'WHERE 1 ';
		$where .= " AND `mode` != 'admin' AND `result` = 8 ";
		if(!empty($uidarr)){
			$where .= " AND `role_id` IN ( $uidarr ) ";
		}
		$sql = "SELECT `role_id`,`money`,`time` FROM $this->_table where id in (SELECT max(id) from $this->_table $where group by role_id)";
		$rs=$this->_db->Query($sql);
		if(!$rs) return null;
		$_records=array();
		while($row=$this->_db->FetchArray($rs)){
			 $_records[$row['role_id']]['money'] = $row['money'];
			 $_records[$row['role_id']]['time'] = $row['time'];
		}
		$this->_db->FreeResult($rs);
		return $_records;				
	}

}
?>