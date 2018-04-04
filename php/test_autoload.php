<?php
/*****************************************************************
 * 文件名称：test_autoload.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-30 13:51
 * 描    述：
 *****************************************************************/
namespace foo;

function my_autoloader1($class) {
    echo '1 loading class '.$class.PHP_EOL;
}

function my_autoloader2($class) {
    echo '2 loading class '.$class.PHP_EOL;
}

function my_autoloader3($class) {
    echo '3 loading class '.$class.PHP_EOL;
}


spl_autoload_register(__NAMESPACE__.'\my_autoloader1', true);
spl_autoload_register(__NAMESPACE__.'\my_autoloader2', true);
spl_autoload_register(__NAMESPACE__.'\my_autoloader3', true, true);

use D1\D2\D3 as C;

try {
    new \C1\C2\C();
} catch (Exception $e) {
    echo $e->getCode().' '.$e->getMessage();
}
