<?php
/*****************************************************************
 * 文件名称：test_sign.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-16 11:39
 * 描    述：
 *****************************************************************/

$secret = 'fasthand_OULANG_key';
$str = '{"sign":"9903ee798eaeb01f16d15cf013af3cf1","method":"pk.choose","user_id":"875099","receiver_uid":"874898","version":"1.1"}';
$arr = json_decode($str, true);

$signStr = '';
array_walk($arr, function($val, $key) use(&$signStr) {
    if (!empty($key) && $key != 'sign') {
        $signStr .= $key.'='.$val.'&';
    }
});
$signStr = trim($signStr, '&');
echo 'sign str: '.$signStr.PHP_EOL;
$signStr = $secret.$signStr.$secret;
$sign = strtolower(md5($signStr));
echo 'org sign: '.$arr['sign'].PHP_EOL;
echo 'new sign: '.$sign.PHP_EOL;

$signArr = array(); 
array_walk($arr, function($val, $key) use(&$signArr) {
    if (!empty($key) && $key != 'sign') {
        $signArr[$key] = $val;
    }
});
ksort($signArr); 

$signStr = '';
array_walk($signArr, function($val, $key) use(&$signStr) {
    $signStr .= $key.'='.$val.'&';
});
$signStr = trim($signStr, '&');

echo 'sign str: '.$signStr.PHP_EOL;
$signStr = $secret.$signStr.$secret;
$sign = strtolower(md5($signStr));
echo 'org sign: '.$arr['sign'].PHP_EOL;
echo 'new sign: '.$sign.PHP_EOL;
