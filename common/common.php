<?php
/**
@desciption: 公共模块加载入口
*/
//error_reporting(E_ALL);
define('COMM_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
if(!defined('THIS_DATETIME')) define('THIS_DATETIME', time());
include_once(COMM_DIR.'conf/comm.config.php');
include_once(COMM_DIR.'conf/map.config.php');
include_once(COMM_DIR.'libs/cls_model.php');
include_once(COMM_DIR.'libs/cls_validation.php');
include_once(COMM_DIR.'libs/cls_control.php');
include_once(COMM_DIR.'libs/cls_entry.php');
?>