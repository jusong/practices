<?php
$redis=new Redis();
$result=$redis->connect('127.0.0.1',6379);
for ($i = 0; $i < 10; $i++) {
	$res = $redis->lpop('begin');
	var_dump($res, json_decode($res));
}
