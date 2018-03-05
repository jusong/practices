<?php
require_once 'common.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;

define("SERVER_HOST", "121.199.42.40");
define("SERVER_SOCKET_PORT", 9511);
define("SERVER_HTTP_PORT", 80);
define("SERVER_PATH", "/test/server.php");

// Init 
if (array_search('--http', $argv)) {
	$socket = new THttpClient(SERVER_HOST, SERVER_HTTP_PORT, SERVER_PATH);
} else {
	$socket = new TSocket(SERVER_HOST, SERVER_SOCKET_PORT);
}
$transport = new TBufferedTransport($socket, 1024, 1024);
//$protocol = new TBinaryProtocol($transport);
$protocol = new TCompactProtocol($transport);
?>
