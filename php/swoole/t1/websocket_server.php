<?php

/* swoole_timer_after(30000, function() { */
/*     echo "after 3000ms.\n"; */
/* }); */

$ws = new swoole_websocket_server("0.0.0.0", 9514);

//监听打开事件
$ws->on("open", function($ws, $request) {
    //print_r($request);
    $ws->push($request->fd, "hello, welcome\n");
    $ws->push($request->fd, "Server: hello\n");
    
    swoole_timer_tick(20000, function($timer_id) {
        echo $timer_id."\n";
        $ws->push($request->fd, "Server: hello {$timer_id}\n");
    });
});

//监听消息事件
$ws->on("message", function($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "Server: {$frame->data}");
});

//监听关闭事件
$ws->on("close", function($ws, $fd) {
    echo "Client: {$fd} is closed\n";
});

$ws->start();
?>