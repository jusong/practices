<?php
$server = new swoole_server("0.0.0.0", 9512, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据发送
$server->on("Packet", function($server, $data, $clientInfo) {
    print_r($clientInfo);
    $server->sendto($clientInfo['address'], $clientInfo['port'], "Server: ".$data);
});

//启动
$server->start();
?>