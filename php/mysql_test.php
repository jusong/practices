<?php
$dbHost = '121.199.42.40';
$dbUser = 'fasthand';
$dbPasswd = 'fastHand_db_12345665';
$dbName = 'fastHand';

$dbLink = mysql_connect($dbHost, $dbUser, $dbPasswd);
if (empty($dbLink)) {
    die('connect failed!'.PHP_EOL);
}

mysql_select_db ($dbName, $dbLink);
mysql_query("SET NAMES UTF8");

//SELECT suc
$result = mysql_query('select id from fasthand_user limit 3');
var_dump($result);
var_dump(mysql_num_rows($result));
if (!empty($result)) {
    while ($row = mysql_fetch_array($result)) {
        var_dump($row);
    }
}

//SELECT false
$result = mysql_query('select id from fasthand_user where id=0');
var_dump($result);
var_dump(mysql_num_rows($result));
if (!empty($result)) {
    while ($row = mysql_fetch_array($result)) {
        var_dump($row);
    }
}

//UPDATE suc
$result = mysql_query('update fasthand_user set update_time=now() where id in (1, 2, 3)');
var_dump($result);
var_dump(mysql_affected_rows());

//UPDATE false
$result = mysql_query('update fasthand_user set update_time=now() where id=0');
var_dump($result);
var_dump(mysql_affected_rows());

mysql_close();