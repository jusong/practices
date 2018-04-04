<?php
/*****************************************************************
 * 文件名称：test_swoole.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-23 16:51
 * 描    述：
 *****************************************************************/

$workers = array();
$workerNum = 5;

for($i = 0; $i < $workerNum; $i++) {
    $child = new swoole_process('ise', false);
    $pid = $child->start();
    $workers[$pid] = $child;
    $child->write($i + 1);
    echo 'CHILD '.($i + 1).PHP_EOL;
}

while(count($workers)) {
    echo 'WAIT'.PHP_EOL;
    $ret = swoole_process::wait();
    if ($ret) {
        unset($workers[$ret['pid']]);
    }
}

function ise(swoole_process $worker) {

    $txtFile = '/home/jiafd/ise_cn/cn_sentence1.txt';
    $audioFile = '/home/jiafd/ise_cn/cn_sentence.wav';

    $id = $worker->read();

    $txt = trim(file_get_contents($txtFile));
    $audio = file_get_contents($audioFile);
    $txt = chr(0xef).chr(0xbb).chr(0xbf).$txt;

    try {
        $xf = new xfyun('59cdf93b');
        $raw = $xf->ise($txt, $audio, xfyun::READ_SENTENCE_CN);
        $res = iconv('GBK', 'UTF-8', $raw);
        //var_dump($res);
        //var_dump($txt, strlen($txt));
        file_put_contents('/tmp/ise'.$id, print_r($res, true));
    } catch (Exception $e) {
        var_dump($e->getCode(), $e->getMessage());
    }
}
