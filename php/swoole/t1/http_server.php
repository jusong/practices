<?php
$server = new swoole_http_server("0.0.0.0", "9513");
$server->on("request", function($request, $response) {
    $response->header("Content-Type", "text/html; charset=utf8;");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$server->start();
?>