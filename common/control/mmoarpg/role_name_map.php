<?php
class role_name_map extends cls_control{	
	public function query($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 0, $count = false, $field = '*'){
		$res = $this->load('cls_role_name_map','mmoarpg')->records($where,$orderby,$join,$page,$pagesize,$count,$field);
		if($res){
			return $this->result(1,'','',$res);
		} else{
			return $this->result(0);
		}
	}
	
	public function parseinfo($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 1, $count = false, $field = '*'){
		$res = $this->load('cls_role_name_map','mmoarpg')->records($where,$orderby,$join,$page,$pagesize,$count,$field);
		if($res){
			foreach ($res as $key => $value){
				$userarr = explode('_',$value['plat_user_name']);
				$len = count($userarr);
				$xnum = $len - 1;//下划线个数
				$server = $userarr[$xnum];//服务器INDEX 0/1/2/3... abc_cde_aa_0

				$pname = "";
				for($i=0;$i<$xnum;$i++){
					$pname .= $userarr[$i]."_";
				}
				$res[$key]['plat_user_name'] = rtrim($pname, '_');
				$res[$key]['server']= $server;
			}
			return $this->result(1,'','',$res);
		} else{
			return $this->result(0);
		}
	}
	

	public function getbyid($id){
		$res = $this->load('cls_role_name_map','mmoarpg')->getrecordbyid($id);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function selectinname($rolename,$condition='',$field=''){
		$res = $this->load('cls_role_name_map','mmoarpg')->setectinname($rolename,$condition,$field);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}

}
?>