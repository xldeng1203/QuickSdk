<?php
class role extends cls_control{	
	
	public function query($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 0, $count = false, $field = '*'){
		$counts = $this->load('cls_role','mmoarpg')->records($where,'',$join,$page,$pagesize,true);
		if(empty($counts)) {
			return $this->result(1,'','','',0,$page,$pagesize);
		}else{
			$res = $this->load('cls_role','mmoarpg')->records($where,$orderby,$join,$page,$pagesize,$count,$field);
			return $this->result(1,'','',$res,$counts,$page,$pagesize);
		}
	}
	

	public function getbyid($id){
		$res = $this->load('cls_role','mmoarpg')->getrecordbyid($id);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function getlistbysex($country = true){
		$res = $this->load('cls_role','mmoarpg')->getlistbysex($country);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function getlistbylevel($where=''){
		$res = $this->load('cls_role','mmoarpg')->getlistbylevel($where);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function getcount($where = ''){
		$res = $this->load('cls_role','mmoarpg')->count($where);
		if(empty($res)){
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function getlistbyscene($where = ''){
		$res = $this->load('cls_role','mmoarpg')->getlistbyscene($where);
		if(empty($res)){
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	/**
	 * 返回角色总数
	 * 
	 * @author liguoyi
	 * @param string $where
	 */
	public function getrolecount($where=''){
		return $this->load('cls_role','mmoarpg')->getrolecount($where);
	}

}
?>