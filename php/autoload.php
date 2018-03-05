<?php
function __autoload($class) {
	//include_once($class.'.class.php');
	echo $class.PHP_EOL;
	exit;
}
$t = new Name\Home\T();
var_dump($t);


