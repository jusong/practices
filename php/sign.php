<?php

function data_sign($data, $secret = 'Mdw3adPElLiweioIU@#$@98w454$%^!42344') {

    $data   =   is_array($data) ? $data : (array)$data;
    $secret =   $secret ? : C('DATA_SIGN_SECRET');

    ksort($data);
    $signStr = '';
    array_walk($data, function($val, $key) use(&$signStr) {
        if ('sign' !== $key && $val != '' && !is_array($val)) {
            $signStr .= $key.'='.$val.'&';
        }
    });
    $signStr = $signStr.'key='.$secret;

    return sha1($signStr);
}


echo data_sign(md5('123456')).PHP_EOL;
