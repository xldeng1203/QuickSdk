<?php
class command extends cls_control{	
	
	public function add($arr ='',$replace=false){
		$res = $this->load('cls_command','mmoarpg')->insert($arr,$replace);
		if($res){
			return $this->result(1,'','',$res);
		} else{
			return $this->result(0);
		}
	}
	
	public function query($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 0, $count = false, $field = '*'){
		$counts = $this->load('cls_command','mmoarpg')->records($where,'',$join,$page,$pagesize,true);
		if(empty($counts)) {
			return $this->result(1,'','','',0,$page,$pagesize);
		}else{
			$res = $this->load('cls_command','mmoarpg')->records($where,$orderby,$join,$page,$pagesize,$count,$field);
			return $this->result(1,'','',$res,$counts,$page,$pagesize);
		}
	}
	
	public function getlistbytype($type,$page,$pagesize){
		$res = $this->load('cls_command','mmoarpg')->getlistbytype($type,$page,$pagesize);
		if(!empty($res)){
			return $this->result(1,'','',$res);
		}
	}
	

	public function getbyid($id){
		$res = $this->load('cls_command','mmoarpg')->getrecordbyid($id);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}


}
?>