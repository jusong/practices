<?php

$str = 'jiafnagdongsdfsd980989sdf';

$base64Str = base64_encode($str);
echo $base64Str.PHP_EOL;

$urlStr = urlencode($base64Str);
echo $urlStr.PHP_EOL;

$str = base64_decode(urldecode($urlStr));
echo $str.PHP_EOL;
