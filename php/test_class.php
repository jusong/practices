<?php
/*****************************************************************
 * 文件名称：test_class.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-26 13:12
 * 描    述：
 *****************************************************************/
ini_set('error_report', 'Off');
class C {

    public $name;

    public function __set($name, $value) {
        $this->$name = $value;
        echo 'SET '.$name.' = '.$value.PHP_EOL;
    }

    public function __get($name) {
        echo 'GET '.$name.PHP_EOL;
        $this->$name = 'NULL';
        return $this->$name;
    }

    public function __destruct() {
        echo '__destruct called'.PHP_EOL;
    }
}

try {
    $c = new C();
    $c->id = 100;
    throw new Exception('hello');
    echo $c->id.PHP_EOL;
} catch (Exception $e) {
    var_dump($e);
}
