<?php
/*****************************************************************
 * 文件名称：test_get.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-26 12:52
 * 描    述：
 *****************************************************************/

$_GET['PHPSESSID'] = 'test';
session_start();
var_dump($_GET, $_POST, $_COOKIE, $_SESSION);
var_dump(session_id());
$_SESSION['name'] = 'jiafd';
$_SESSION['user'] = array(
    'name'  => 'jiafd',
    'age'   => 100,
    'score' => 100
);

