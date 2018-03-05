<?php
require_once('dao/datasourceFactroy.php');
require_once('util/logTool.php');
$conn = DatasourceFactroy::getDatasource('fastHand');

echo '#test1'.PHP_EOL;
$res = $conn->execute('select id,nick,username,role from fasthand_user limit 10');
while ($row = $conn->nextRow($res)) {
    echo json_encode($row).PHP_EOL;
}

echo '#test2'.PHP_EOL;
$res = $conn->execute('select id,nick,username,role from fasthand_user where id < 0');
while ($row = $conn->nextRow($res)) {
    echo json_encode($row).PHP_EOL;
}

echo '#test3'.PHP_EOL;
$res = $conn->execute('select count(1) from fasthand_user');
if ($row = $conn->nextRow($res)) {
    echo json_encode($row).PHP_EOL;
}

echo '#test4'.PHP_EOL;
$res = $conn->execute('update fasthand_user set update_time=now() where id=1');
var_dump($res);


echo '#test5'.PHP_EOL;
$res = $conn->execute('update fasthand_user set update_time=now() where id=0');
var_dump($res);
