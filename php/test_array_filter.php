<?php
/*****************************************************************
 * 文件名称：test_array_filter.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-16 11:57
 * 描    述：
 *****************************************************************/

$a = array(
    1, 2, 3, 4, 5, 6, 7,
    'name'  => 'jiafd',
    'age'   => 28
);

$b = array_filter($a, function($v) {
    return is_numeric($v);
});
var_dump($b);


$x = array(1, 2, 3);
$y = array(4, 5, 6);
$z = array(7, 8, 9);
$r = array_map(function($v1, $v2, $v3) {
    return array($v1, $v2, $v3);
}, $x, $y, $z);
var_dump($r);
print_r($r);
