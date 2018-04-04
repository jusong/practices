<?php
/*****************************************************************
 * 文件名称：test_preg.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-04-03 11:35
 * 描    述：
 *****************************************************************/

$username = "asdad asdf\n\rasdf,sdfs
asdf\r\nasdsdfas           \asdf
    qasdfasdfasdf223434f";

$usernameArray = preg_split('/,|，|\s/', $username, null, PREG_SPLIT_NO_EMPTY);
var_dump($usernameArray);
