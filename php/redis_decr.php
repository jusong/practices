<?php
ini_set('default_socket_timeout', -1);

try {
	$redis=new Redis();
	$redis->connect('127.0.0.1',6379);
	$seq = $redis->brpop('begin', 0);
	$count = $redis->decr('goods');
	file_put_contents('/tmp/redis.log', microtime(true).' '.json_encode($seq).' '.json_encode($count).PHP_EOL, FILE_APPEND);
} catch (Exception $e) {
	var_dump($e);
}
