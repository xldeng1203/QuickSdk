<?php
require('db.php');

function query_row_0($sql)
{
    global $db_conn;

    $db_conn->query($sql);
}

function query_row_1($sql, $cnum)
{
    global $db_conn;
	
    $result = $db_conn->query($sql);
	if (!$result) {
        exit("query fail: {$sql}");
    }
    $rows = $result->fetch_all();
    if (empty($rows)) {
        exit("result empty");
    }

    $cvar = array();
	for ($i = 0; $i < $cnum; ++$i) {
       $cvar[] = $rows[0][$i]; 
    }
    $result->close();

    return $cvar;
}

$daily_type = array(
    1 => "注收比",
    2 => "次日登录流失",
    3 => "次日注册流失",
    4 => "次日流失",
);

$date_time = date('Y-m-d 00:00:00');
//$date_time = '2014-09-17 00:00:00';

function statistic_1($date_time, $days_ago, $col1, $col2)
{
    global $db_game;
    global $db_admin;


    try {
        $result = query_row_1("SELECT COUNT(DISTINCT m.role_id) AS cn, COUNT(DISTINCT r.role_id) AS rn, SUM(m.money) AS mn FROM ( SELECT DISTINCT(q.n2) AS role_id FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL {$days_ago} DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL {$days_ago}-1 DAY) AND q.n1 = 53 AND q.n2 NOT IN ( SELECT DISTINCT (role_id) FROM {$db_game}.role_attr_detail AS a WHERE a.authority_type != 0 ) ) AS r LEFT JOIN (SELECT c.role_id,c.money FROM {$db_admin}.charge AS c WHERE c.time >= UNIX_TIMESTAMP(DATE_SUB('{$date_time}', INTERVAL {$days_ago} DAY)) AND c.time < UNIX_TIMESTAMP('{$date_time}') AND c.mode!='admin') AS m ON r.role_id = m.role_id WHERE 1;", 3);

        $cn = $result[0];
        $rn = $result[1];
        $mn = $result[2];
		
        query_row_0("INSERT INTO {$db_admin}.stat_daily (time, type, n1, n2, n3, n4, n5, f1, f2, f3, f4, f5) VALUES (DATE_SUB('{$date_time}', INTERVAL {$days_ago} DAY), '1', '{$rn}', '0', '0', '0', '0', '0', '0', '0', '0', '0');");
        query_row_0("UPDATE {$db_admin}.stat_daily SET n1 = '{$rn}', {$col1} = '{$cn}', {$col2} = '{$mn}' WHERE time = DATE_SUB('{$date_time}', INTERVAL {$days_ago} DAY)");

    } catch (Exception $ex) {
        echo($ex->getMessage());
    }
}

//statistic_1($date_time, 1, "n2", "f2");
//statistic_1($date_time, 3, "n3", "f3");
//statistic_1($date_time, 7, "n4", "f4");
//statistic_1($date_time, 30, "n5", "f5");

function statistic_2($date_time)
{
    global $db_game;
    global $db_admin;

    try {
        $result = query_row_1("SELECT COUNT(DISTINCT q.n2) AS rn FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 2 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.n1 = 54;", 1);
        $n2 = (float)$result[0];
		
        $result = query_row_1("SELECT COUNT(DISTINCT q.n2) AS rn FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 0 DAY) AND q.n1 = 54 AND q.n2 IN (SELECT DISTINCT(q.n2) FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 2 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.n1 = 54);", 1);
        $n1 = (float)$result[0];
		
	$f1 = (float)$n2 > 0 ? (float)$n1 / (float)$n2 : 0;
	query_row_0("INSERT INTO {$db_admin}.stat_daily (time, type, n1, n2, n3, n4, n5, f1, f2, f3, f4, f5) VALUES ('{$date_time}', '2', '{$n1}', '{$n2}', '0', '0', '0', '{$f1}', '0', '0', '0', '0');");

    } catch (Exception $ex) {
        echo($ex->getMessage());
    }
}

statistic_2($date_time);

function statistic_4($date_time)
{
    global $db_game;
    global $db_admin;

    try {
        $result = query_row_1("SELECT COUNT(DISTINCT q.n2) AS rn FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 2 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.n1 = 54;", 1);
        $n1 = (int)$result[0];
        $result = query_row_1("SELECT COUNT(DISTINCT q.n2) AS rn FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 0 DAY) AND q.n1 = 54;", 1);
        $n2 = (int)$result[0];
        $result = query_row_1("SELECT COUNT(DISTINCT q.n2) AS rn FROM {$db_admin}.log_quick AS q WHERE q.time >= DATE_SUB('{$date_time}', INTERVAL 1 DAY) AND q.time < DATE_SUB('{$date_time}', INTERVAL 0 DAY) AND q.n1 = 53;", 1);
        $n3 = (int)$result[0];

        $f1 = (float)$n1 > 0 ? ((float)$n1 - ((float)$n2 - (float)$n3)) / (float)$n1 : 0;
        query_row_0("INSERT INTO {$db_admin}.stat_daily (time, type, n1, n2, n3, n4, n5, f1, f2, f3, f4, f5) VALUES ('{$date_time}', '4', '{$n1}', '{$n2}', '{$n3}', '0', '0', '{$f1}', '0', '0', '0', '0');");

    } catch (Exception $ex) {
        echo($ex->getMessage() . "\n");
    }
}

statistic_4($date_time);
