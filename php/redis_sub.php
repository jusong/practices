<?php  
$redis=new Redis();  
$redis->connect('127.0.0.1',6379);  
$redis->subscribe(array('ch1'), function ($r, $c, $m) {
		$res = $r->lpop('lst');
		var_dump($r, $c, $m, $res);
		});
