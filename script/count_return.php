<?php 
/**
 * 感恩回馈礼包玩家
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');

//查询数据库中所有有45级的玩家
$where = array('field'=>'level','val'=>'45','type'=>'>=');
$join  = array('table'=>'role_name_map','type'=>'LEFT','left'=>'role.role_id','right'=>'role_name_map.role_id');
$field = 'plat_user_name';
$result = cls_entry::load('role')->query($where,'',$join,'','','',$field);
if(!empty($result['result'])){
	//先清空表，再执行插入操作
	cls_entry::load('count_return')->truncate();
	//执行插入操作
	foreach($result['result'] as $r){
		$insert = cls_entry::load('count_return')->add(array('plat_user_name'=>$r['plat_user_name']));
		if(!$insert){
			$log .= $r['plat_user_name'].',';
		}
	}
}
exit($log);

?>