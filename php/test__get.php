<?php
/*****************************************************************
 * 文件名称：test__get.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-06 15:33
 * 描    述：
 *****************************************************************/

class T {

    private $sub = 'sub';

    public function __get($name) {
        if (property_exists($this, $name)) {
            echo $this->$name.PHP_EOL;
        } else {
            echo 'you want get '.$name.PHP_EOL;
        }
        $this->age = 100;
    }

    public function __set($name, $value) {
        echo "__set $name => $value\n";
        $this->$name = $value;
    }
}

$t = new T();
//$t->name = 'jia';
$t->sub;
