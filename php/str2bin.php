<?php
/*****************************************************************
 * 文件名称：str2bin.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-05 17:24
 * 描    述：
 *****************************************************************/

function str2bin($str) {

    //1.列出每个字符
    $arr = preg_split('/(?<!^)(?!$)/u', $str);

    //2.unpack字符
    $bin = '';
    foreach($arr as &$v) {
        echo $v.PHP_EOL;
        $bin .= pack('N', $v);
    }
    echo $bin.PHP_EOL;
    $str = base64_encode($bin);
    $str = base64_decode($bin);
    var_dump(unpack('C', pack('C', 'i')));
}

/** 
 * * 讲二进制转换成字符串 
 * * @param type $str 
 * * @return type 
 * */  
function bin2str($str) {
    $arr = explode(' ', $str);  
    foreach($arr as &$v){  
        $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));  
    }  

    return join('', $arr);  
} 

var_dump(str2bin("hello world"));
var_dump(pack('A*', 'Hell o  '));

$bin = pack("H", 0x5);
echo "output: " . $bin . "\n";
echo "output: " . ord($bin) . "\n";

$bin = pack('N', 3);
echo "output: " . $bin . "\n";
echo "output: " . chr($bin) . "\n";


$str = '{"name":"hello world"}';

$bin = '';
for($i = 0; $i < strlen($str); $i++) {
    $bin .= pack('N', ord($str[$i]));
}
echo "pack: ".$bin.PHP_EOL;
echo "pack len: ".strlen($bin).PHP_EOL;
$str = base64_encode($bin);
echo "pack: ".$str.PHP_EOL;
