<?php  
$redis=new Redis();  
$res=$redis->connect('127.0.0.1',6379);  
if (!$res) {
	echo 'error'.PHP_EOL;
}
$res = $redis->set('mkey1', 0001, array());
var_dump($res);
if (!$res) {
	echo 'set error'.PHP_EOL;
}
$res = $redis->get('mkey1');
var_dump($res);

echo 'set===================='.PHP_EOL;
//$res = $redis->sadd('mset', 1, 2, 3, 4, 5);
//var_dump($res);

$res = $redis->scard('mset');
var_dump($res);

$res = $redis->spop('mset', 1);
var_dump($res);



function sadd() {
	global $redis;
	$args	= func_num_args();
	if ($args < 2) {
		return false;
	}

	$argv		= func_get_args();
	if ($args == 2) {
		if (is_array($argv[1])) {
			array_unshift($argv[1], $argv[0]);
			return call_user_func_array(array($redis, 'sadd'), $argv[1]);
		} else {
			return $redis->sadd($argv[0], $argv[1]);
		}
	} else {
		var_dump($argv);
		return call_user_func_array(array($redis, 'sadd'), $argv);
	}
}

var_dump(sadd('mset', 8));

$res = $redis->smembers('mset');
var_dump($res);
