<?php
$server = new swoole_server("0.0.0.0", 9511);
$server->addlistener("127.0.0.1", 9512, SWOOLE_UDP);
$server->addlistener("localhost", 9513, SWOOLE_UNIX_DGRAM);
//设置异步任务的工作进程数
$server->set(array("task_worker_num" => 4));

$process = new swoole_process(function($process) use ($server){
    while(true) {
        $msg = $process->read();
        $data = json_decode($msg, true);
        $fd = $data['from_fd'];
        $msgData = $data['data'];
        foreach($server->connections as $conn) {
            if ($conn != $fd) {
                $server->send($conn, $msgData);
            }
        }
    }
});
$server->addprocess($process);

$server->on("workerstart", function($server, $worker_id) {
    if ($server->taskworker) {
        swoole_set_process_name("swoole_task_worker[{$worker_id}]");
    } else {
        swoole_set_process_name("swoole_event_worker[{$worker_id}]");
    }
});

//绑定客户端链接事件
$server->on("connect", function($server, $fd) {
    $name = genName();
    $server->name = $name;
    $server->send($fd, "Your Name: {$name}\n");
    echo "Client Connect: Name: {$name}\n";
});

//绑定接受客户端数据事件
$server->on("receive", function($server, $fd, $from_id, $data) use ($process) {
    /* $server->send($fd, date("Y-m-d H:i:s")); */
    echo "=============================================================\n";
    echo "receive: fd: {$fd}, from_id: {$from_id}, data: {$data}\n";

    if (!$server->name) {
        $name = genName();
        $server->name = $name;
        $server->send($fd, "Your Name: {$name}\n");
        echo "Client Connect: Name: {$name}\n";
    }
    
    $data = array("data" => $server->name.": ".$data, "from_fd" => $fd);
    $server->task(json_encode($data));
    $server->tick(3000, function() use($process, $data) {
        $process->write(json_encode($data));
    });
});

//处理异步任务
$server->on("task", function($server, $task_id, $from_id, $data) use ($process) {
    echo "task: task_id: {$task_id}, from_id: {$from_id}, data: {$data}\n";
    echo "=============================================================\n";
    $process->write($data);
});

$server->on("finish", function(){});

//绑定关闭链接事件
$server->on("close", function($server, $fd) {
    echo "Client: Close.\n";
});

//启动服务
$server->start();

function genName($len = 10) {
    $len = $len > 0 ? $len : 10;
    $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $name = '';
    for($i = 0; $i < $len; $i++) {
        $name .= substr($str, rand(0, 61), 1);
    }
    return $name;
}
?>