<?php
$dbHost = '121.199.42.40';
$dbUser = 'fasthand';
$dbPasswd = 'fastHand_db_12345665';
$dbName = 'fastHand';
$str = "SELECT * FROM fasthand_advertising WHERE 1=1 AND ad_page = 'webIndex' AND status = '1' AND instr('level1,level2,level3,level4,level5', ad_position)";
echo 'mysql: '.mysql_real_escape_string($str).PHP_EOL;
$dns = 'mysql:host='.$dbHost.';port=3306;dbname='.$dbName;
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
);
$dbLink = new PDO($dns, $dbUser, $dbPasswd, $options);
if (empty($dbLink)) {
    die('connect failed!'.PHP_EOL);
}

$str = "SELECT * FROM fasthand_advertising WHERE 1=1 AND ad_page = 'webIndex' AND status = '1' AND instr('level1,level2,level3,level4,level5', ad_position)";
echo 'mysql: '.mysql_real_escape_string($str).PHP_EOL;
exit;

$dbLink->exec('SET SQL_MODE=ANSI_QUOTES');

$str = "SELECT * FROM fasthand_advertising WHERE 1=1 AND ad_page = 'webIndex' AND status = '1' AND instr('level1,level2,level3,level4,level5', ad_position)";
//echo $str.PHP_EOL;
//$str = $dbLink->quote($str);
//echo $str.PHP_EOL;

//SELECT suc
$result = $dbLink->query($str);
if (empty($result)) {
    var_dump($dbLink->errorCode(), $dbLink->errorInfo());
    exit;
}
var_dump($result);
var_dump($result->rowCount());
if (!empty($result)) {
    while ($row = $result->fetch()) {
        var_dump($result->rowCount());
        var_dump($row);
    }
}
exit;

//SELECT false
$result1 = $dbLink->query('select id from fasthand_user where id in (0, 0, -1)');
var_dump($result1);
var_dump($result1->rowCount());
if (!empty($result1)) {
    while ($row = $result1->fetch()) {
        var_dump($row);
    }
    var_dump($row);
}

//UPDATE suc
$result = $dbLink->exec('update fasthand_user set update_time=now() where id in (1, 2, 3)');
var_dump($result);
//var_dump(mysql_affected_rows());

//UPDATE false
$result = $dbLink->exec('update fasthand_user set update_time=now() where id=0');
var_dump($result);
//var_dump(mysql_affected_rows());

//mysql_close();