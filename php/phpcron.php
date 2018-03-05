<?php
/**
 * PHP实现定时任务cron功能
 */

$config = array(
    array(3, 'date'),
);

$timefile = 'phpcron.tmf';
$logfile  = '/tmp/phpcron.log';

$taskArr = array();
if (file_exists($timefile)) {
    $tmpTimeArr = json_decode(file_get_contents($timefile), true);
}

foreach ($config as $_config) {
    list($time, $cmd)   = $_config;
    $taskId             = md5($time.$cmd);

    if (isset($tmpTimeArr[$taskId])) {
        $taskArr[$taskId] = array($cmd, $time, $tmpTimeArr[$taskId]);
    } else {
        $taskArr[$taskId] = array($cmd, $time, 0);
    }
}

do {
    $now = time();
    foreach ($taskArr as $taskId => $task) {
        list($cmd, $time, $lastTime) = $task;
        if ($now - $lastTime >= $time) {
            execCmd($cmd, $now);
            $taskArr[$taskId][2] = $now;
        }
    }
    file_put_contents($timefile, json_encode($taskArr));
    sleep(2);
} while(1);

function execCmd($cmd, $time) {
    global $logfile;
    $lastOut = system($cmd, $res);
    file_put_contents($logfile, $time.' ['.$cmd.'] '.$res.' '.$lastOut.PHP_EOL, FILE_APPEND);
}
