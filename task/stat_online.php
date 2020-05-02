<?php
require('db.php');

/*
argv = {
    'h' = 统计小时数据
    'd' = 统计每天数据
}

type = {
    1 = 每分钟在线

    2 = 每小时平均值
    3 = 每小时高峰值
    4 = 每小时低峰值

    5 = 每天平均值
    6 = 每天高峰值
    7 = 每天低峰值
}
*/

switch ($argv[1])
{
case 'h':
    statistic(2, date('Y-m-d h:00:00'), 'HOUR');
    break;

case 'd':
    statistic(5, date('Y-m-d 00:00:00'), 'DAY');
    break;
}

function statistic($type, $time, $unit)
{
    global $db_game;
    global $db_admin;
    global $db_conn;

    $last_type = $type - 3;
    if ($last_type < 1)
    {
        $last_type = 1;
    }

    $sql = "SELECT AVG(online), MAX(online), MIN(online) FROM {$db_admin}.stat_online WHERE type = {$last_type} AND time >= DATE_SUB('{$time}', INTERVAL 1 {$unit}) AND time < '{$time}';";
	$result = $db_conn->query($sql);
    if (!$result) {
        exit("query fail: {$sql}");
    }

    $rows = $result->fetch_all();
    if (empty($rows)) {
        exit("result empty");
    }

    $avg = $rows[0][0];
    $max = $rows[0][1];
    $min = $rows[0][2];
    $result->close();

    $sql = "INSERT INTO {$db_admin}.stat_online (time, type, online) VALUES (DATE_SUB('{$time}', INTERVAL 1 {$unit}), {$type}, {$avg}),(DATE_SUB('{$time}', INTERVAL 1 {$unit}), {$type} + 1, {$max}), (DATE_SUB('{$time}', INTERVAL 1 {$unit}), {$type} + 2, {$min});";

    $reuslt = $db_conn->query($sql);
    if (!$result) {
        exit("insert fail: {$sql}");
    }
}

