<?php
/**
 * 元宝表
 *
 */
class accountgold extends cls_control{	
	public function query($where = '', $orderby = '', $join = '', $page = 1, $pagesize = 0, $count = false, $field = '*'){
		$counts = $this->load('cls_accountgold','mmoarpg')->records($where,'',$join,$page,$pagesize,true);
		if(empty($counts)) {
			return $this->result(1,'','','',0,$page,$pagesize);
		}else{
			$res = $this->load('cls_accountgold','mmoarpg')->records($where,$orderby,$join,$page,$pagesize,$count,$field);
			return $this->result(1,'','',$res,$counts,$page,$pagesize);
		}
	}
	
	public function getbyid($id){
		$res = $this->load('cls_accountgold','mmoarpg')->getrecordbyid($id);
		if(empty($res)) {
			return $this->result(0);
		}else{
			return $this->result(1,'','',$res);
		}
	}
	
	public function add($post){
		$res = $this->load('cls_accountgold','mmoarpg')->insert($post);
		if($res){
			return $this->result(1,'','',$res);
		} else{
			return $this->result(0);
		}
	}
	
	public function upnew($post,$where){
		$res = $this->load('cls_accountgold','mmoarpg')->update($post,$where);
		if($res){
			return $this->result(1,'','',$res);
		} else{
			return $this->result(0);
		}
	}	

	public function add_gold($plat_user_name, $gold){
		$accountinfo = $this->load ( 'cls_accountgold', 'mmoarpg' )->records ( array (
				'field' => 'plat_user_name',
				'val' => $plat_user_name
		), '', '', '', '', '', 'gold,gold_history' );
			
		if (empty ( $accountinfo )) { // 如果不存在充值记录，则新插入
			$paccount = array (
					'plat_user_name' => $plat_user_name,
					'gold' => $gold,
					'gold_history' => $gold,
					'last_get_gold_time' => 0
			);
			return $this->load ( 'cls_accountgold', 'mmoarpg' )->insert ( $paccount );

		} else { // 否则，更新记录
			$paccount = array (
					'gold' => $accountinfo [0] ['gold'] + intval ( $gold ), 
					'gold_history' => $accountinfo [0] ['gold_history'] + intval ( $gold )
			);
		
			return $this->load ( 'cls_accountgold', 'mmoarpg' )->update ( $paccount, array (
					'field' => 'plat_user_name',
					'val' => $plat_user_name
			) );
		}
	}	
	
	public function setAutoCommit(){
		$this->load('cls_accountgold','mmoarpg')->setAutoCommit();
	}
	
	public function begin(){
		$this->load('cls_accountgold','mmoarpg')->begin();
	}

	public function rollback(){
		$this->load('cls_accountgold','mmoarpg')->rollback();
	}

	public function commit(){
		$this->load('cls_accountgold','mmoarpg')->commit();
	}	

}
?>