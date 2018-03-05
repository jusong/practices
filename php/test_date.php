<?php

$now = date('Y-m-d H:i:s');
echo $now.PHP_EOL;
$ago = date('Y-m-d H:i:s', strtotime($now.'-60min'));
echo $ago.PHP_EOL;
