<?php
$count = 10;
$wk = array();

for($i = 0; $i < $count; $i++) {
	$p = new swoole_process(function (swoole_process $worker) {
			echo 'hello'.PHP_EOL;
			}, false, false);
	$pid = $p->start();
	$wk[] = $pid;
}

while(1) {
	if(count($wk)){
		$ret = swoole_process::wait();
		var_dump($res);
	}else{
		break;
	}
}
