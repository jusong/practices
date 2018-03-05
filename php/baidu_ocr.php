<?php

$__ROOT__ = dirname(__FILE__);
require_once($__ROOT__.'/baidu_token.php');

function ocr($imgPath) {

    $ak	= 'pbXSf16i2XnfdzMjiFjP1Y14';
    $sk = 'yGoqllHmVG2qiDHkC1UCLjZBUUuKBqya';
    $accessToken	= access_token($ak, $sk);

    $url   = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token='.$accessToken;
    $data	= array(
        'image'				=> base64_encode(file_get_contents($imgPath)),
        /* 'language_type'		=> 'CHN_ENG', */
        /* 'detect_direction'	=> true, */
        /* 'detect_language'	=> true, */
        /* 'probability'		=> true */
    );
    return request_post($url, $data);
}

$res	= ocr($argv[1]);
var_dump($res);