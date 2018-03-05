<?php
require_once 'common.php';

use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TFramedTransport;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TCompactProtocol;

define("SERVER_HOST", "localhost");
define("SERVER_PORT", 9511);

$param_arr = getopt('h:p:t:');
$host = isset($param_arr['h']) && empty($param_arr['h']) ? $param_arr['h'] : SERVER_HOST;
$port = isset($param_arr['p']) && empty($param_arr['p']) ? $param_arr['p'] : SERVER_PORT;
$socket = new TSocket($host, $port);

if (isset($param_arr['t']) && $param_arr['t'] == "nonblk") {
	$transport = new TFramedTransport($socket);
} else {
	$transport = new TBufferedTransport($socket, 1024, 1024);
}
//$protocol = new TBinaryProtocol($transport);
$protocol = new TCompactProtocol($transport);
?>
