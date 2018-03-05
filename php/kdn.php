<?php

$url = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';
$mchid = '1308702';
$key = '80142c5a-bd2a-4053-b22d-b862150f39e4';

if ($argc != 3) {
    die('param error'.PHP_EOL);
}

$reqdata = array(
    'ShipperCode' => $argv[1],
    'LogisticCode' => $argv[2]
);
$reqdata = json_encode($reqdata);
$datasign = urlencode(base64_encode(md5($reqdata.$key)));

$data = array(
    'RequestData' => urlencode($reqdata),
    'EBusinessID' => $mchid,
    'RequestType' => '1002',
    'DataSign'    => $datasign,
    'DataType'    => '2'
);

$dataArr = array();
array_walk($data, function ($value, $key) use(&$dataArr) {
    $dataArr[] = ($key.'='.$value);
});
$data = implode('&', $dataArr);

$curl = curl_init();
$options = array(
    CURLOPT_URL => $url,
    CURLOPT_HEADER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        'Content-type: application/x-www-form-urlencoded;charset=utf-8',
    )
);
curl_setopt_array($curl, $options);
$res = curl_exec($curl);
$errno = curl_errno($curl);
if ($errno) {
    die(curl_error($curl).PHP_EOL);
}
if (200 != curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    die(curl_getinfo($curl, CURLINFO_HTTP_CODE).PHP_EOL);
}
echo $res.PHP_EOL;
var_dump(json_decode($res, true));
