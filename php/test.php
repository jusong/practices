<?php

$a = array(
    'a' => 1,
    'b' => 2,
    'c' => 3,
    'd' => 4
);

var_dump('key', key($a));
var_dump('current', current($a));
var_dump('next', next($a));
var_dump('prev', prev($a));
var_dump('end', end($a));

reset($a);
while (list($k, $v) = each($a)) {
    var_dump($k.' => '.$v);
}
