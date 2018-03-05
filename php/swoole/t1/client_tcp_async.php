<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

$client->on("connect", function($cli) {
    $cli->send("hello world");
});

$client->on("receive", function($cli, $data) {
    echo $data."\n";
    sleep(5);
    $cli->send("hello world");
});

$client->on("error", function($cli) {
    echo "Connect faield\n";
});

$client->on("close", function($cli) {
    echo "Connection close\n";
});

$client->connect("0.0.0.0", 9511, 0.5);
?>