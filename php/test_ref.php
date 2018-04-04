<?php
/*****************************************************************
 * 文件名称：test_ref.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-28 13:36
 * 描    述：
 *****************************************************************/

class C {

    private $buf;

    public function __construct(&$buf = '') {
        $this->buf = &$buf;
    }

    public function cut() {
        $this->buf = substr($this->buf, 1);
    }

    public function add($str) {
        $this->buf .= $str;
    }
}

$str = 'jiafd';
$c = new C($str);
var_dump($str);
$c->cut();
var_dump($str);
$c->cut();
var_dump($str);
$c->add($str);
var_dump($str);
