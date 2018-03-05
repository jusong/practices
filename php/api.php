<?php
$url = "http://open.api.shenzhoujiajiao.net/v1/card/verfitem";

$param = array(
	'account' => '2d56bc964cd5b57ff4a27e231ab219174dd72278',
	'itemId' => 2284,
	'myCardId' => 63519,
	'yuyue_tag' => 2,
	'event_type' => 'cardActivity',
	'qrsign' => 'e8bbe1e6d1cd87c2089279b429c85fc7',
	't' => time(),
	);

ksort($param);
$urlParam = '';
foreach ($param as $key => $value) {
	$urlParam .= $key.'='.$value.'&';
}
$s = '1c412276022339d8892c666c6f86e866854bf3ea';
$urlParam = trim($urlParam, '&');
$sign = md5($s.$urlParam.$s);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $urlParam.'&sign='.$sign);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
curl_close($ch);
var_dump($res);
