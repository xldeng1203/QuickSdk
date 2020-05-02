<?php
/**
 * 补偿修为
 * 1、2014-02-28 12:00:00 前创建的角色 2、等级大于等于51 3、补偿 14W 修为
 */
define('SCRIPT',dirname(__FILE__));
define('ADMIN_DIR',str_replace('script','admintool',SCRIPT));
include_once(ADMIN_DIR.'/source/global.config.inc.php');
include_once(ADMIN_DIR.'/source/config.inc.php');

$result = cls_entry::load ( 'role' )->query ( " WHERE level>=51 AND create_time<=UNIX_TIMESTAMP('2014-02-28 12:00:00')", '', '', '', '', '', "role_id" );

$role_list=$result['result'];

echo("角色数 = " . count($role_list) . "\n");

foreach ($role_list as $role_id)
{

    echo("角色 = " . $role_id['role_id'] . "\n");

    $mail = array (
            'uid' => $role_id['role_id'],
            'recv_time' => THIS_DATETIME,
            'kind' => 2, 
            'coin' => 0,
            'coin_bind' => 0,
            'gold' => 0,
            'gold_bind' => 0,
            'item_id1' => 0,
            'item_num1' => 0,
            'item_invalid_time1' => 0,
            'subject' => "任务修为补偿",
            'content' => "应新版本内容的需要，特此补偿您已完成任务中未获取的修为奖励，祝您游戏愉快~",
            'virtual_item_data' => "00000000000000000000000000000000000000000000000000000000E02202000000000000000000",
            );

    $mail_insert = cls_entry::load( 'systemmail' )->add ( $mail ); 
    if (! $mail_insert) {
        echo("插入失败 = " . $role_id['role_id'] . "\n");
    }

    cls_entry::load('command')->add(array( 'creator' => 'background', 'createtime' => time(), 'type' => 2, 'cmd' => "newnotice 2 {$role_id['role_id']}" ));
}

?>
