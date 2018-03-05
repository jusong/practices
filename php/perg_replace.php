<?php
$table = 'fasthand_user_ddads';
echo preg_replace_callback('/_([a-zA-Z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $table).PHP_EOL;
