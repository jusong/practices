<?php
/*****************************************************************
 * 文件名称：test_xml.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-09 10:55
 * 描    述：
 *****************************************************************/

$str = file_get_contents('/tmp/ise.log');
$pos = strpos($str, ',');
$xml = substr($str, $pos + 1);
$res =  simplexml_load_string($xml);
$attributes = $res->read_sentence->rec_paper->read_sentence->attributes();
var_dump($attributes);
var_dump((float)$attributes->total_score);
