<?php

$__ROOT__ = dirname(__FILE__);
require_once($__ROOT__.'/baidu_token.php');

function detect($imgPath) {

    $ak	= 'pbXSf16i2XnfdzMjiFjP1Y14';
    $sk = 'yGoqllHmVG2qiDHkC1UCLjZBUUuKBqya';
    $accessToken	= access_token($ak, $sk);

    $url   = 'https://aip.baidubce.com/rest/2.0/face/v1/detect?access_token='.$accessToken;
    $data	= array(
        'image'				=> base64_encode(file_get_contents($imgPath)),
        'max_face_num'		=> 10,
        'face_fields'		=> 'age,beauty,expression,faceshape,gender,glasses,landmark,race,qualities'
    );
    return request_post($url, $data);
}

$res	= detect($argv[1]);
var_dump($res);