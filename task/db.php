<?php

date_default_timezone_set("PRC");
ini_set('display_errors', 'On');
error_reporting(E_ALL);
ini_set('memory_limit', '8192M');
set_time_limit(0);

$db_host  = '127.0.0.1:3306'; 
$db_user  = 'root';
$db_pass  = 'root';

$db_game = 'ucb_s1';
$db_admin = 'ucbb_s1';

$db_conn = new mysqli($db_host, $db_user, $db_pass);

if ($db_conn->connect_errno) {
	exit("mysql connect fail");
} else {
	//echo("mysql connect success");
}
