<?php  
$store=100;  
$redis=new Redis();  
$result=$redis->connect('127.0.0.1',6379);  
$redis->ltrim('begin', 1, 0);
$redis->multi();
for($i=1;$i<=$store;$i++){  
	$a = array();
	for ($j = 0; $j < $i; $j++) {
		$a[$j] = $i * $j;
	}
    $redis->rpush('begin', json_encode($a));
}
$redis->exec();
var_dump($redis->llen('begin'));
