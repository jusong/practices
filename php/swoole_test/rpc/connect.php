<?php
require_once 'common.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TJSONProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TClientFramedTransport;
use Thrift\Exception\TException;

//define("SERVER_HOST", "localhost");
define("SERVER_HOST", "121.199.42.40");
define("SERVER_HTTP_PORT", 80);
define("SERVER_SOCKET_PORT", 9511);
define("SERVER_PATH", "/test/server.php");

// Init 
if (array_search('--http', $argv)) {
	$socket = new THttpClient(SERVER_HOST, SERVER_HTTP_PORT, SERVER_PATH);
} else {
	$socket = new TSocket(SERVER_HOST, SERVER_SOCKET_PORT);
	//$socket = new TSocketPool(SERVER_HOST, SERVER_SOCKET_PORT);
	$socket->setSendTimeout(200);
	//$socket->setRecvTimeout(15000);
	//$socket->setRecvTimeout(3000);
}
//$transport = new TBufferedTransport($socket, 1024, 1024);
$transport = new TFramedTransport($socket);
$protocol = new TBinaryProtocol($transport, true, true);
//$protocol = new TJSONProtocol($transport);
?>
