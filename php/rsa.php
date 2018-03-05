<?php

$str = '["1115502342341202"]';


function encrypt($str, $key_path) {
    $key = file_get_contents($key_path);
    //$rsa = openssl_get_privatekey($key);
    $rsa = openssl_get_publickey($key);
    $keySize = 2048;
    $step = $keySize / 8 - 11;
    $strArr = str_split($str, $step);
    $res = '';
    foreach ($strArr as $str) {
        //openssl_private_encrypt($str, $encrypt, $rsa);
        openssl_public_encrypt($str, $encrypt, $rsa);
        $res .= $encrypt;
    }
    openssl_free_key($rsa);
    return base64_encode($res);
}

function decrypt($str, $key_path) {
    $key = file_get_contents($key_path);
    //$rsa = openssl_get_publickey($key);
    $rsa = openssl_get_privatekey($key);
    $keySize = 2048;
    $step = $keySize / 8;
    $str = base64_decode($str);
    $res = '';
    for($i = 0; $i < strlen($str) / $step; $i++) {
        $slip = substr($str, $i * $step, $step);
        //openssl_public_decrypt($slip, $decrypt, $rsa);
        openssl_private_decrypt($slip, $decrypt, $rsa);
        $res .= $decrypt;
    }
    openssl_free_key($rsa);
    return $res;
}

echo $str.PHP_EOL;
$encrypt = encrypt($str, 'app_public_key.pem');
echo $encrypt.PHP_EOL;
$str = decrypt(substr($encrypt, 0, -3), 'app_private_key.pem');
echo $str.PHP_EOL;
