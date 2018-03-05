<?php  
$redis=new Redis();  
$redis->connect('127.0.0.1',6379);  
$redis->publish('ch1', 'hello');
