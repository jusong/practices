<?php

try{
    $db = new PDO('mysql:host=127.0.0.1;dbname=test', 'root', 'jia', array(
                PDO::ATTR_CASE => PDO::CASE_NATURAL, //保留数据库驱动返回的列名
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )); 
    echo 'CONNECTED'.PHP_EOL;
    $str = 'n%a"m\'e';
    $str = "jia'df\"asd";
    print strlen($db->quote($str)).': '.$db->quote($str).PHP_EOL;
    print strlen($str).': '.$str.PHP_EOL;

} catch (Exception $e) {
    var_dump($e);
}
