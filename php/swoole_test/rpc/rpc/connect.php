<?php
require_once 'common.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

define("SERVER_HOST", "localhost");
define("SERVER_HTTP_PORT", 80);
define("SERVER_SOCKET_PORT", 9090);
define("SERVER_PATH", "/test/server.php");

// Init 
if (array_search('--http', $argv)) {
	$socket = new THttpClient(SERVER_HOST, SERVER_HTTP_PORT, SERVER_PATH);
} else {
	$socket = new TSocket(SERVER_HOST, SERVER_SOCKET_PORT);
}
$transport = new TBufferedTransport($socket, 1024, 1024);
$protocol = new TBinaryProtocol($transport);
?>
